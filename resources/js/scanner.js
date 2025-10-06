import { createApp } from "vue";
import QrScanner from "qr-scanner";
import ScannerComponent from "./components/ScannerComponent.vue";
import axios from "axios";

// Set up CSRF token for axios
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
window.axios.defaults.headers.common["X-CSRF-TOKEN"] = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

// Make QrScanner available globally and ensure it's ready
window.QrScanner = QrScanner;

// Create the app and pass QrScanner as a global property
const app = createApp(ScannerComponent);
app.config.globalProperties.$qrScanner = QrScanner;
app.mount("#app");
