import confetti from 'canvas-confetti'

export function confettiFromElement(element, opts) {
  const { top, height, left, width } = element.getBoundingClientRect()
  const x = (left + width / 2) / window.innerWidth
  const y = (top + height / 2) / window.innerHeight
  const origin = { x, y }
  confetti({ origin, ...opts })
}
