import { createApp } from "vue";
import axios from "axios";

// Set up CSRF token for axios
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
window.axios.defaults.headers.common["X-CSRF-TOKEN"] = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

// Simple test component
const TestComponent = {
    template: `
        <div style="padding: 20px; text-align: center; color: white; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
            <h1>QR Scanner Test</h1>
            <p>Vue.js is working!</p>
            <button @click="testApi" style="padding: 10px 20px; background: #4ade80; color: white; border: none; border-radius: 5px; cursor: pointer;">
                Test API
            </button>
            <div v-if="message" style="margin-top: 20px; padding: 10px; background: rgba(255,255,255,0.2); border-radius: 5px;">
                {{ message }}
            </div>
        </div>
    `,
    data() {
        return {
            message: "",
        };
    },
    methods: {
        async testApi() {
            try {
                this.message = "Testing API...";
                const response = await window.axios.post("/api/validate-qr", {
                    qr_data:
                        '{"type":"attendance","event_id":1,"token":"test","attendee_name":"Test","attendee_email":"test@test.com"}',
                });
                this.message = "API Response: " + JSON.stringify(response.data);
            } catch (error) {
                this.message = "API Error: " + error.message;
            }
        },
    },
};

createApp(TestComponent).mount("#app");
