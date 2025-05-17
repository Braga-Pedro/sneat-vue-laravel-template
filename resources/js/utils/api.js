import { ofetch } from 'ofetch'
import { clearSessionAndRedirect } from './logout.js'

export const $api = ofetch.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || '/api',
  credentials: 'include',
  async onRequest({ options }) {
    const token = localStorage.getItem('accessToken')

    if (token) {
      options.headers = {
        ...options.headers,
        Authorization: `Bearer ${token}`,
      }
    }
  },
  onResponseError({ response, error }) {
    console.error('API Response Error: ', response, error)

    if (response?.status === 401) {
      clearSessionAndRedirect()
    }
  },
})


