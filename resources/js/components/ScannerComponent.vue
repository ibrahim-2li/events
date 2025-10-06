<template>
  <div class="scanner-container">
    <!-- Header -->
    <div class="header">
      <h1 class="title">ماسح رمز QR</h1>
      <p class="subtitle">امسح رمز QR لتسجيل الحضور</p>
    </div>

    <!-- Scanner Section -->
    <div class="scanner-section">
      <div class="scanner-wrapper" v-if="!isScanning && !scanResult">
        <div class="scanner-placeholder">
          <div class="scanner-icon">📱</div>
          <p>اضغط على "بدء المسح" لتفعيل الكاميرا</p>
        </div>
      </div>

      <div class="scanner-wrapper" v-if="isScanning">
        <video ref="videoElement" class="scanner-video"></video>
        <div class="scanner-overlay">
          <div class="scanner-frame"></div>
          <p class="scanner-instruction">وجه الكاميرا نحو رمز QR</p>
        </div>
      </div>

      <!-- Scan Result -->
      <div class="result-section" v-if="scanResult">
        <div class="result-card" :class="scanResult.success ? 'success' : 'error'">
          <div class="result-icon">
            {{ scanResult.success ? '✅' : '❌' }}
          </div>
          <h3 class="result-title">{{ scanResult.success ? 'تم تسجيل الحضور بنجاح!' : 'فشل في تسجيل الحضور' }}</h3>
          <p class="result-message">{{ scanResult.message }}</p>

          <div v-if="scanResult.success && scanResult.data" class="result-details">
            <div class="detail-item">
              <span class="label">اسم الحاضر:</span>
              <span class="value">{{ scanResult.data.attendee_name }}</span>
            </div>
            <div class="detail-item">
              <span class="label">اسم الحدث:</span>
              <span class="value">{{ scanResult.data.event_title }}</span>
            </div>
            <div class="detail-item">
              <span class="label">وقت التسجيل:</span>
              <span class="value">{{ scanResult.data.checked_in_at }}</span>
            </div>
            <div class="detail-item">
              <span class="label">سجل بواسطة:</span>
              <span class="value">{{ scanResult.data.checked_in_by }}</span>
            </div>
          </div>

          <div v-if="scanResult.already_checked_in" class="already-checked-in">
            <div class="detail-item">
              <span class="label">تم التسجيل مسبقاً في:</span>
              <span class="value">{{ scanResult.checked_in_at }}</span>
            </div>
            <div class="detail-item">
              <span class="label">سجل بواسطة:</span>
              <span class="value">{{ scanResult.checked_in_by }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Controls -->
    <div class="controls">
      <button
        v-if="!isScanning && !scanResult"
        @click="startScanning"
        class="btn btn-primary"
        :disabled="loading"
      >
        {{ loading ? 'جاري التحميل...' : 'بدء المسح' }}
      </button>

      <button
        v-if="isScanning"
        @click="stopScanning"
        class="btn btn-secondary"
      >
        إيقاف المسح
      </button>

      <button
        v-if="scanResult"
        @click="resetScanner"
        class="btn btn-primary"
      >
        مسح جديد
      </button>
    </div>

    <!-- Loading Overlay -->
    <div v-if="loading" class="loading-overlay">
      <div class="loading-spinner"></div>
      <p>جاري معالجة رمز QR...</p>
    </div>
  </div>
</template>

<script>
// QrScanner is available globally from scanner.js

export default {
  name: 'ScannerComponent',
  data() {
    return {
      isScanning: false,
      qrScanner: null,
      scanResult: null,
      loading: false,
      hasCamera: false
    }
  },
  async mounted() {
    // Get QrScanner from global properties or window
    const QrScanner = this.$qrScanner || window.QrScanner;

    if (!QrScanner) {
      console.error('QrScanner library failed to load');
      alert('فشل في تحميل مكتبة QR Scanner');
      return;
    }

    // Check if camera is available
    try {
      this.hasCamera = await QrScanner.hasCamera();
      if (!this.hasCamera) {
        alert('الكاميرا غير متاحة. يرجى التأكد من إعطاء الإذن للوصول للكاميرا.');
      }
    } catch (error) {
      console.error('Error checking camera:', error);
      alert('خطأ في التحقق من الكاميرا: ' + error.message);
    }
  },
  beforeUnmount() {
    this.stopScanning();
  },
  methods: {
    async startScanning() {
      if (!this.hasCamera) {
        alert('الكاميرا غير متاحة');
        return;
      }

      try {
        this.isScanning = true;
        this.scanResult = null;

        // Wait for the next tick to ensure the video element is rendered
        await this.$nextTick();

        // Check if video element exists
        if (!this.$refs.videoElement) {
          throw new Error('Video element not found');
        }

        // Get QrScanner from global properties or window
        const QrScanner = this.$qrScanner || window.QrScanner;

        if (!QrScanner) {
          throw new Error('QrScanner library not loaded');
        }

        console.log('QrScanner available:', !!QrScanner);
        console.log('Video element:', this.$refs.videoElement);
        console.log('QrScanner.hasCamera:', typeof QrScanner.hasCamera);

        this.qrScanner = new QrScanner(
          this.$refs.videoElement,
          (result) => this.handleScan(result),
          {
            highlightScanRegion: true,
            highlightCodeOutline: true,
            preferredCamera: 'environment'
          }
        );

        console.log('QrScanner instance created:', !!this.qrScanner);

        await this.qrScanner.start();
        console.log('QrScanner started successfully');
      } catch (error) {
        console.error('Error starting scanner:', error);
        console.error('Error stack:', error.stack);
        alert('حدث خطأ في تشغيل الكاميرا: ' + error.message);
        this.isScanning = false;
      }
    },

    stopScanning() {
      if (this.qrScanner) {
        this.qrScanner.stop();
        this.qrScanner.destroy();
        this.qrScanner = null;
      }
      this.isScanning = false;
    },

    async handleScan(result) {
      this.loading = true;
      this.stopScanning();

      try {
        const response = await window.axios.post('/api/validate-qr', {
          qr_data: result.data,
          checked_in_by: 'Scanner'
        });

        this.scanResult = response.data;
      } catch (error) {
        console.error('Error validating QR code:', error);
        this.scanResult = {
          success: false,
          message: error.response?.data?.message || 'حدث خطأ في معالجة رمز QR'
        };
      } finally {
        this.loading = false;
      }
    },

    resetScanner() {
      this.scanResult = null;
      this.isScanning = false;
      this.loading = false;
    }
  }
}
</script>

<style scoped>
.scanner-container {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 20px;
  color: white;
}

.header {
  text-align: center;
  margin-bottom: 30px;
}

.title {
  font-size: 2.5rem;
  font-weight: bold;
  margin: 0 0 10px 0;
  text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.subtitle {
  font-size: 1.2rem;
  margin: 0;
  opacity: 0.9;
}

.scanner-section {
  width: 100%;
  max-width: 500px;
  margin-bottom: 30px;
}

.scanner-wrapper {
  position: relative;
  width: 100%;
  height: 400px;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0,0,0,0.3);
  background: rgba(255,255,255,0.1);
  backdrop-filter: blur(10px);
}

.scanner-placeholder {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100%;
  text-align: center;
}

.scanner-icon {
  font-size: 4rem;
  margin-bottom: 20px;
}

.scanner-video {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.scanner-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: rgba(0,0,0,0.3);
}

.scanner-frame {
  width: 250px;
  height: 250px;
  border: 3px solid #fff;
  border-radius: 20px;
  position: relative;
  margin-bottom: 20px;
}

.scanner-frame::before,
.scanner-frame::after {
  content: '';
  position: absolute;
  width: 30px;
  height: 30px;
  border: 3px solid #4ade80;
}

.scanner-frame::before {
  top: -3px;
  left: -3px;
  border-right: none;
  border-bottom: none;
}

.scanner-frame::after {
  bottom: -3px;
  right: -3px;
  border-left: none;
  border-top: none;
}

.scanner-instruction {
  color: white;
  font-size: 1.1rem;
  text-align: center;
  margin: 0;
  text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
}

.result-section {
  width: 100%;
}

.result-card {
  background: rgba(255,255,255,0.95);
  border-radius: 20px;
  padding: 30px;
  text-align: center;
  color: #333;
  box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.result-card.success {
  border-left: 5px solid #4ade80;
}

.result-card.error {
  border-left: 5px solid #ef4444;
}

.result-icon {
  font-size: 3rem;
  margin-bottom: 20px;
}

.result-title {
  font-size: 1.5rem;
  font-weight: bold;
  margin: 0 0 15px 0;
  color: #333;
}

.result-message {
  font-size: 1.1rem;
  margin: 0 0 20px 0;
  color: #666;
}

.result-details {
  background: rgba(0,0,0,0.05);
  border-radius: 10px;
  padding: 20px;
  margin-top: 20px;
}

.already-checked-in {
  background: rgba(255,193,7,0.1);
  border-radius: 10px;
  padding: 20px;
  margin-top: 20px;
  border: 1px solid #ffc107;
}

.detail-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 8px 0;
  border-bottom: 1px solid rgba(0,0,0,0.1);
}

.detail-item:last-child {
  border-bottom: none;
}

.label {
  font-weight: bold;
  color: #555;
}

.value {
  color: #333;
  text-align: left;
}

.controls {
  display: flex;
  gap: 15px;
  flex-wrap: wrap;
  justify-content: center;
}

.btn {
  padding: 15px 30px;
  border: none;
  border-radius: 50px;
  font-size: 1.1rem;
  font-weight: bold;
  cursor: pointer;
  transition: all 0.3s ease;
  min-width: 150px;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-primary {
  background: linear-gradient(135deg, #4ade80, #22c55e);
  color: white;
  box-shadow: 0 5px 15px rgba(74, 222, 128, 0.3);
}

.btn-primary:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(74, 222, 128, 0.4);
}

.btn-secondary {
  background: linear-gradient(135deg, #ef4444, #dc2626);
  color: white;
  box-shadow: 0 5px 15px rgba(239, 68, 68, 0.3);
}

.btn-secondary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
}

.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0,0,0,0.8);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  color: white;
}

.loading-spinner {
  width: 50px;
  height: 50px;
  border: 5px solid rgba(255,255,255,0.3);
  border-top: 5px solid white;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-bottom: 20px;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Responsive Design */
@media (max-width: 768px) {
  .scanner-container {
    padding: 10px;
  }

  .title {
    font-size: 2rem;
  }

  .scanner-wrapper {
    height: 300px;
  }

  .scanner-frame {
    width: 200px;
    height: 200px;
  }

  .result-card {
    padding: 20px;
  }

  .btn {
    padding: 12px 24px;
    font-size: 1rem;
    min-width: 120px;
  }
}
</style>
