```vue
<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';

const page = usePage();
const theme = ref('dark');
const event = computed(() => page.props.event);
const eventDate = new Date(event.value.starts_at).getTime();
const countdown = ref({ days: '00', hours: '00', minutes: '00', seconds: '00' });

let timer = null;

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    attending: '',
    guests: 1,
    dietary: '',
});

const successMessage = computed(() => page.props.flash?.success);

function setTheme(next) {
    theme.value = next;
    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
}

function updateCountdown() {
    const difference = eventDate - Date.now();

    if (difference <= 0) {
        countdown.value = { days: '00', hours: '00', minutes: '00', seconds: '00' };
        return;
    }

    countdown.value = {
        days: String(Math.floor(difference / (1000 * 60 * 60 * 24))).padStart(2, '0'),
        hours: String(Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0'),
        minutes: String(Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0'),
        seconds: String(Math.floor((difference % (1000 * 60)) / 1000)).padStart(2, '0'),
    };
}

function submit() {
    form.post('/rsvp', {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
}

onMounted(() => {
    setTheme(localStorage.getItem('theme') || 'dark');
    updateCountdown();
    timer = setInterval(updateCountdown, 1000);
});

onUnmounted(() => {
    if (timer) {
        clearInterval(timer);
    }
});
</script>

<template>
    <div class="theme-toggle">
        <div
            class="theme-option light-option"
            :class="{ active: theme === 'light' }"
            title="Light Mode"
            @click="setTheme('light')"
        ></div>

        <div
            class="theme-option dark-option"
            :class="{ active: theme === 'dark' }"
            title="Dark Mode"
            @click="setTheme('dark')"
        ></div>
    </div>

    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <div class="container">
        <section class="hero-section">
            <div class="hero-content">
                <div class="hero-badge">{{ event.badge }}</div>

                <h1 class="hero-title">{{ event.title }}</h1>

                <p class="hero-description">
                    {{ event.description }}
                </p>
            </div>

            <div class="hero-details">
                <div class="detail-card">
                    <div class="detail-header">
                        <div class="detail-icon">
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <rect
                                    x="3"
                                    y="6"
                                    width="18"
                                    height="15"
                                    rx="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                                <path
                                    d="M3 10h18M8 3v3M16 3v3"
                                    stroke-linecap="round"
                                />
                            </svg>
                        </div>

                        <div class="detail-info">
                            <div class="detail-label">Date</div>
                            <div class="detail-value">{{ event.date_label }}</div>
                        </div>
                    </div>
                </div>

                <div class="detail-card">
                    <div class="detail-header">
                        <div class="detail-icon">
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <circle cx="12" cy="12" r="9" />
                                <path
                                    d="M12 7v5l3.5 2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </div>

                        <div class="detail-info">
                            <div class="detail-label">Time</div>
                            <div class="detail-value">{{ event.time_label }}</div>
                        </div>
                    </div>
                </div>

                <div class="detail-card">
                    <div class="detail-header">
                        <div class="detail-icon">
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"
                                    stroke-linejoin="round"
                                />
                                <circle cx="12" cy="9" r="2.5" />
                            </svg>
                        </div>

                        <div class="detail-info">
                            <div class="detail-label">Location</div>
                            <div class="detail-value">{{ event.location }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="countdown-wrapper">
            <div class="countdown-inner">
                <div class="countdown-label">Time Until Event</div>

                <div class="countdown">
                    <div class="countdown-item">
                        <div class="countdown-number">{{ countdown.days }}</div>
                        <div class="countdown-unit">Days</div>
                    </div>

                    <div class="countdown-item">
                        <div class="countdown-number">{{ countdown.hours }}</div>
                        <div class="countdown-unit">Hours</div>
                    </div>

                    <div class="countdown-item">
                        <div class="countdown-number">{{ countdown.minutes }}</div>
                        <div class="countdown-unit">Minutes</div>
                    </div>

                    <div class="countdown-item">
                        <div class="countdown-number">{{ countdown.seconds }}</div>
                        <div class="countdown-unit">Seconds</div>
                    </div>
                </div>
            </div>
        </div>

        <section class="rsvp-section">
            <div class="rsvp-inner">
                <div
                    class="success-message"
                    :class="{ show: successMessage }"
                >
                    <div class="success-icon">✓</div>
                    <div class="success-text">{{ successMessage }}</div>
                </div>

                <div class="form-header">
                    <h2 class="form-title">
                        Confirm Your <strong>Attendance</strong>
                    </h2>

                    <p class="form-subtitle">
                        We'd love to have you join us for this special occasion
                    </p>
                </div>

                <form @submit.prevent="submit">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">First Name *</label>

                            <input
                                id="first_name"
                                v-model="form.first_name"
                                type="text"
                                placeholder="John"
                                required
                            >

                            <p v-if="form.errors.first_name">
                                {{ form.errors.first_name }}
                            </p>
                        </div>

                        <div class="form-group">
                            <label for="last_name">Last Name *</label>

                            <input
                                id="last_name"
                                v-model="form.last_name"
                                type="text"
                                placeholder="Doe"
                                required
                            >

                            <p v-if="form.errors.last_name">
                                {{ form.errors.last_name }}
                            </p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address *</label>

                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            placeholder="john.doe@example.com"
                            required
                        >

                        <p v-if="form.errors.email">
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>

                        <input
                            id="phone"
                            v-model="form.phone"
                            type="tel"
                            placeholder="(555) 000-0000"
                        >
                    </div>

                    <div class="form-group">
                        <label>Will You Be Attending? *</label>

                        <div class="rsvp-options">
                            <div class="rsvp-option">
                                <input
                                   <input
    id="notAttending"
    v-model="form.attending"
    type="radio"
    value="no"
>
<label for="notAttending">
    Can't make it
</label>
</div>

<div class="rsvp-option">
    <input
        id="maybe"
        v-model="form.attending"
        type="radio"
        value="maybe"
    >
    <label for="maybe">
        Not sure yet
    </label>
</div>
</div>

<p v-if="form.errors.attending">
    {{ form.errors.attending }}
</p>
</div>

<div class="form-group">
    <label for="guests">Number of Guests</label>

    <select id="guests" v-model="form.guests">
        <option :value="1">1 Guest</option>
        <option :value="2">2 Guests</option>
        <option :value="3">3 Guests</option>
        <option :value="4">4 Guests</option>
        <option :value="5">5+ Guests</option>
    </select>
</div>

<div class="form-group">
    <label for="dietary">Dietary Restrictions</label>

    <textarea
        id="dietary"
        v-model="form.dietary"
        placeholder="Please let us know of any dietary preferences or restrictions..."
    ></textarea>
</div>

<button
    type="submit"
    class="submit-btn"
    :disabled="form.processing"
>
    {{ form.processing ? 'Sending...' : 'Confirm RSVP' }}
</button>
</form>
</div>
</section>

<footer>
    <p>
        Design:
        <a
            rel="nofollow"
            href="https://tooplate.com"
            target="_blank"
        >
            Tooplate
        </a>
        | Professional Invitation Portal
    </p>
</footer>
</div>
</template>