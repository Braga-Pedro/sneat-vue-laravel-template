import { ofetch } from 'ofetch'

export const api = ofetch.create({
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

function clearSessionAndRedirect() {
  // Remove tudo relacionado à sessão
  const keysToRemove = [
    // 'userData',
    'accessToken',
    // 'userDataModule',
    // 'selectedModule',
  ]

  keysToRemove.forEach(key => {
    localStorage.removeItem(key)
  })

  // Redireciona para login
  window.location.href = '/login'
}
