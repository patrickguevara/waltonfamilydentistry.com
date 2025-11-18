<script setup lang="ts">
import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Label from '@/Components/UI/Form/Label.vue'
import Input from '@/Components/UI/Form/Input.vue'
import Textarea from '@/Components/UI/Form/Textarea.vue'
import ErrorMessage from '@/Components/UI/Form/ErrorMessage.vue'
import { useAccessibility } from '@/composables/useAccessibility'

const page = usePage()
const { announceToScreenReader, focusFirstError } = useAccessibility()

const form = ref({
  name: '',
  email: '',
  phone: '',
  message: '',
})

const processing = ref(false)
const errors = computed(() => page.props.errors || {})
const successMessage = computed(() => (page.props.flash as any)?.success || null)

function submit() {
  processing.value = true

  router.post('/contact', form.value, {
    preserveScroll: true,
    onSuccess: () => {
      form.value = {
        name: '',
        email: '',
        phone: '',
        message: '',
      }
      announceToScreenReader('Your message has been sent successfully!')
    },
    onError: () => {
      focusFirstError()
      announceToScreenReader('Please correct the errors in the form.')
    },
    onFinish: () => {
      processing.value = false
    },
  })
}
</script>

<template>
  <AppLayout
    title="Contact Us - Walton Family Dentistry"
    description="Get in touch with our dental practice. We're here to answer your questions and schedule your appointment."
  >
    <div class="py-12">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="text-center mb-12">
          <h1 class="text-4xl font-bold text-black sm:text-5xl mb-4">Contact Us</h1>
          <p class="text-xl text-gray-700 max-w-3xl mx-auto">
            Have questions or ready to schedule an appointment? We'd love to hear from you.
            Fill out the form below or give us a call.
          </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
          <!-- Contact Form -->
          <div>
            <h2 class="text-2xl font-bold text-black mb-6">Send Us a Message</h2>

            <!-- Success Message -->
            <div
              v-if="successMessage"
              role="alert"
              class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800"
            >
              {{ successMessage }}
            </div>

            <form @submit.prevent="submit" class="space-y-6">
              <!-- Name -->
              <div>
                <Label for="name" required>Full Name</Label>
                <Input
                  id="name"
                  v-model="form.name"
                  type="text"
                  :error="errors.name"
                  :disabled="processing"
                  required
                  autocomplete="name"
                />
                <ErrorMessage v-if="errors.name" :message="errors.name" />
              </div>

              <!-- Email -->
              <div>
                <Label for="email" required>Email Address</Label>
                <Input
                  id="email"
                  v-model="form.email"
                  type="email"
                  :error="errors.email"
                  :disabled="processing"
                  required
                  autocomplete="email"
                />
                <ErrorMessage v-if="errors.email" :message="errors.email" />
              </div>

              <!-- Phone -->
              <div>
                <Label for="phone">Phone Number</Label>
                <Input
                  id="phone"
                  v-model="form.phone"
                  type="tel"
                  :error="errors.phone"
                  :disabled="processing"
                  autocomplete="tel"
                />
                <ErrorMessage v-if="errors.phone" :message="errors.phone" />
              </div>

              <!-- Message -->
              <div>
                <Label for="message" required>Message</Label>
                <Textarea
                  id="message"
                  v-model="form.message"
                  :error="errors.message"
                  :disabled="processing"
                  rows="6"
                  required
                />
                <ErrorMessage v-if="errors.message" :message="errors.message" />
              </div>

              <!-- Submit Button -->
              <button
                type="submit"
                :disabled="processing"
                class="w-full inline-flex items-center justify-center px-8 py-3 text-base font-medium text-white bg-black rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition-colors duration-200 min-h-[44px] disabled:opacity-50 disabled:cursor-not-allowed"
              >
                {{ processing ? 'Sending...' : 'Send Message' }}
              </button>
            </form>
          </div>

          <!-- Contact Information -->
          <div>
            <h2 class="text-2xl font-bold text-black mb-6">Get In Touch</h2>

            <div class="space-y-8">
              <!-- Office Info will be populated via middleware -->
              <div>
                <h3 class="text-lg font-semibold text-black mb-2">Visit Us</h3>
                <address class="not-italic text-gray-700">
                  <p>123 Main Street</p>
                  <p>Austin, TX 78701</p>
                </address>
              </div>

              <div>
                <h3 class="text-lg font-semibold text-black mb-2">Call Us</h3>
                <a
                  href="tel:5125551234"
                  class="text-gray-700 hover:text-black focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 rounded transition-colors duration-200"
                >
                  (512) 555-1234
                </a>
              </div>

              <div>
                <h3 class="text-lg font-semibold text-black mb-2">Email Us</h3>
                <a
                  href="mailto:info@waltonfamilydentistry.com"
                  class="text-gray-700 hover:text-black focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 rounded transition-colors duration-200"
                >
                  info@waltonfamilydentistry.com
                </a>
              </div>

              <div>
                <h3 class="text-lg font-semibold text-black mb-3">Office Hours</h3>
                <dl class="space-y-1 text-gray-700">
                  <div class="flex justify-between">
                    <dt class="font-medium">Monday - Thursday:</dt>
                    <dd>8:00 AM - 5:00 PM</dd>
                  </div>
                  <div class="flex justify-between">
                    <dt class="font-medium">Friday:</dt>
                    <dd>8:00 AM - 2:00 PM</dd>
                  </div>
                  <div class="flex justify-between">
                    <dt class="font-medium">Saturday - Sunday:</dt>
                    <dd>Closed</dd>
                  </div>
                </dl>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
