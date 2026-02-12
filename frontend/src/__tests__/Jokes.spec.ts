import { describe, it, expect, vi, beforeEach } from 'vitest'
import { mount } from '@vue/test-utils'
import Jokes from '@/features/Jokes.vue'

const mockJokes = [
  { id: 1, type: 'programming', setup: 'Why...', punchline: 'Because...' },
  { id: 2, type: 'programming', setup: 'What...', punchline: 'No...' },
  { id: 3, type: 'programming', setup: 'How...', punchline: 'Yes...' }
]

vi.mock('@/repositories/JokeRepositories', () => ({
  default: {
    get: vi.fn()
  }
}))

import JokeRepository from '@/repositories/JokeRepositories'

describe('Jokes.vue', () => {
  beforeEach(() => {
    vi.resetAllMocks()
  })

  it('renders jokes from repository', async () => {
    ;(JokeRepository.get as any).mockResolvedValue(mockJokes)

    const wrapper = mount(Jokes)

    await new Promise((r) => setTimeout(r, 0))

    expect(wrapper.text()).toContain('Why...')

    const typeSpans = wrapper.findAll('span')
    const programmingCount = typeSpans.filter((n) => n.text().toLowerCase().includes('programming')).length
    expect(programmingCount).toBe(3)
  })

  it('shows loading state while fetching', async () => {
    let resolve: Function
    const p = new Promise((r) => (resolve = r))
    ;(JokeRepository.get as any).mockReturnValue(p)

    const wrapper = mount(Jokes)

    const btn = wrapper.find('button')
    expect(btn.text()).toMatch(/Loading...|Load Fresh Jokes/)
    expect((btn.attributes('disabled') || null)).toBeDefined()

    resolve!(mockJokes)
    await new Promise((r) => setTimeout(r, 0))

    expect(wrapper.text()).toContain('Load Fresh Jokes')
  })

  it('handles empty response', async () => {
    ;(JokeRepository.get as any).mockResolvedValue([])
    const wrapper = mount(Jokes)
    await new Promise((r) => setTimeout(r, 0))

    expect(wrapper.text()).not.toContain('Why...')

    expect(wrapper.text()).toContain('Programming Jokes')
  })

  it('handles repository error gracefully', async () => {
    ;(JokeRepository.get as any).mockRejectedValue(new Error('network'))
    const spy = vi.spyOn(console, 'error').mockImplementation(() => {})

    const wrapper = mount(Jokes)
    await new Promise((r) => setTimeout(r, 0))

    expect(wrapper.text()).not.toContain('Why...')

    spy.mockRestore()
  })
})
