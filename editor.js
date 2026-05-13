/**
 * PHOTOBOOTH CAMERA & FILTER
 * File: js/camera.js
 */

class PhotoBooth {
    constructor() {
        this.video = null;
        this.canvas = null;
        this.stream = null;
        this.photos = [];
        this.currentFilter = 'normal';
        this.countdown = 3;
        this.isCapturing = false;
        this.init();
    }

    /**
     * Initialize camera dan setup event listeners
     */
    async init() {
        this.video = document.getElementById('video');
        this.canvas = document.getElementById('canvas');
        
        if (!this.video) return;

        try {
            // Request webcam access
            this.stream = await navigator.mediaDevices.getUserMedia({
                video: { width: { ideal: 1280 }, height: { ideal: 720 } },
                audio: false
            });

            this.video.srcObject = this.stream;
            this.video.play();

            // Setup filter buttons
            this.setupFilterButtons();

            // Setup capture buttons
            this.setupCaptureButtons();

            console.log('✅ Camera initialized');
        } catch (error) {
            console.error('❌ Camera error:', error);
            this.showToast('Izinkan akses kamera untuk menggunakan photobooth', 'error');
        }
    }

    /**
     * Setup filter button listeners
     */
    setupFilterButtons() {
        const filterButtons = document.querySelectorAll('.filter-btn');
        filterButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                // Remove active class dari semua button
                filterButtons.forEach(b => b.classList.remove('active'));
                // Add active ke button yang diklik
                e.target.classList.add('active');
                // Apply filter
                this.currentFilter = e.target.dataset.filter;
                this.applyFilter(this.currentFilter);
            });
        });
    }

    /**
     * Apply CSS filter ke video
     */
    applyFilter(filterName) {
        const filterMap = {
            'normal': 'none',
            'bw': 'grayscale(100%)',
            'sepia': 'sepia(100%)',
            'vintage': 'sepia(60%) saturate(80%) hue-rotate(-10deg)',
            'bright': 'brightness(1.3) contrast(1.1)',
            'cool': 'hue-rotate(200deg) saturate(1.2)'
        };

        if (this.video) {
            this.video.style.filter = filterMap[filterName] || 'none';
        }
    }

    /**
     * Setup capture buttons
     */
    setupCaptureButtons() {
        const captureButtons = document.querySelectorAll('[data-photos]');
        captureButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const numPhotos = parseInt(btn.dataset.photos);
                this.startCapture(numPhotos);
            });
        });
    }

    /**
     * Start capturing photos with countdown
     */
    async startCapture(numPhotos) {
        if (this.isCapturing) return;
        
        this.isCapturing = true;
        this.photos = [];
        
        // Disable buttons saat capturing
        document.querySelectorAll('[data-photos]').forEach(btn => {
            btn.disabled = true;
        });

        // Show countdown modal
        const modal = document.getElementById('countdownModal');
        if (modal) {
            modal.classList.add('active');
        }

        for (let i = 0; i < numPhotos; i++) {
            // Play shutter sound
            this.playShutterSound();
            
            // Capture photo
            const photoData = this.capturePhoto();
            this.photos.push(photoData);

            // Update countdown display
            const countdownText = document.getElementById('countdownText');
            if (countdownText) {
                countdownText.textContent = `${numPhotos - i} foto...`;
            }

            // Delay antar foto (3 detik)
            await this.delay(3000);
        }

        // Close countdown modal
        if (modal) {
            modal.classList.remove('active');
        }

        // Composite photos
        const composite = this.compositePhotos(numPhotos);

        // Save to database
        this.savePhotos(composite);

        this.isCapturing = false;
        
        // Enable buttons kembali
        document.querySelectorAll('[data-photos]').forEach(btn => {
            btn.disabled = false;
        });
    }

    /**
     * Capture single photo dari video
     */
    capturePhoto() {
        if (!this.canvas || !this.video) return null;

        const ctx = this.canvas.getContext('2d');
        
        // Set canvas size sesuai video
        this.canvas.width = this.video.videoWidth;
        this.canvas.height = this.video.videoHeight;

        // Apply filter ke canvas juga
        ctx.filter = window.getComputedStyle(this.video).filter;
        
        // Draw video ke canvas
        ctx.drawImage(this.video, 0, 0);

        // Return canvas as blob
        return this.canvas.toDataURL('image/jpeg', 0.9);
    }

    /**
     * Composite multiple photos menjadi strip/grid
     */
    compositePhotos(numPhotos) {
        const compositeCanvas = document.createElement('canvas');
        const ctx = compositeCanvas.getContext('2d');

        let layout = {
            cols: 1,
            rows: numPhotos
        };

        // Determine layout
        if (numPhotos === 2) {
            layout = { cols: 2, rows: 1 };
        } else if (numPhotos === 3) {
            layout = { cols: 1, rows: 3 };
        } else if (numPhotos === 4) {
            layout = { cols: 2, rows: 2 };
        } else if (numPhotos === 6) {
            layout = { cols: 3, rows: 2 };
        }

        // Set canvas size
        const photoWidth = 400;
        const photoHeight = 300;
        const gap = 20;

        compositeCanvas.width = (photoWidth + gap) * layout.cols - gap;
        compositeCanvas.height = (photoHeight + gap) * layout.rows - gap;

        // Fill background
        ctx.fillStyle = '#fff';
        ctx.fillRect(0, 0, compositeCanvas.width, compositeCanvas.height);

        // Draw photos
        this.photos.forEach((photo, index) => {
            const img = new Image();
            img.onload = () => {
                const row = Math.floor(index / layout.cols);
                const col = index % layout.cols;
                const x = col * (photoWidth + gap);
                const y = row * (photoHeight + gap);
                
                ctx.drawImage(img, x, y, photoWidth, photoHeight);
            };
            img.src = photo;
        });

        return compositeCanvas.toDataURL('image/jpeg', 0.9);
    }

    /**
     * Save photos ke server
     */
    async savePhotos(photoData) {
        try {
            const response = await fetch('upload.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    photo: photoData,
                    filter: this.currentFilter
                })
            });

            const result = await response.json();

            if (result.success) {
                this.showToast('✅ Foto berhasil disimpan!', 'success');
                
                // Redirect ke editor
                setTimeout(() => {
                    window.location.href = `editor.php?photo_id=${result.photo_id}`;
                }, 1500);
            } else {
                this.showToast(result.error || 'Gagal menyimpan foto', 'error');
            }
        } catch (error) {
            console.error('Upload error:', error);
            this.showToast('Error upload foto', 'error');
        }
    }

    /**
     * Play shutter sound
     */
    playShutterSound() {
        // Create simple beep sound
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();

        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);

        oscillator.frequency.value = 800;
        oscillator.type = 'sine';

        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);

        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.1);
    }

    /**
     * Delay helper
     */
    delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    /**
     * Show toast notification
     */
    showToast(message, type = 'info') {
        const container = document.getElementById('toastContainer') || this.createToastContainer();
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.textContent = message;
        container.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 3000);
    }

    /**
     * Create toast container
     */
    createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'toast-container';
        document.body.appendChild(container);
        return container;
    }
}

// Initialize saat document ready
document.addEventListener('DOMContentLoaded', () => {
    new PhotoBooth();
});
