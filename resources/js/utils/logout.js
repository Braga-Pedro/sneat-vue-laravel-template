export function clearSessionAndRedirect() {
  // Remove tudo relacionado à sessão
  const keysToRemove = [
    'dataUser',
    'accessToken',
    'dataModule',
    'selectedModule',
  ]

  keysToRemove.forEach(key => {
    localStorage.removeItem(key)
  })

  // Redireciona para login
  window.location.href = '/login'
}
