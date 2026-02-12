<script lang="ts">
import {defineComponent} from 'vue'
import JokeRepository from '@/repositories/JokeRepositories'

const limitCount = 3

interface Joke {
  id: number
  type: string
  setup: string
  punchline: string
}

export default defineComponent({
  name: "jokes",
  mounted() {
    this.fetchJokes()
  },
  data() {
    return {
      jokes: [] as Joke[],
      loading: false
    }
  },
  methods: {
    async fetchJokes() {
      this.loading = true
      const filters = {
        "filters[limit]": limitCount
      }
      try {
        const data = await JokeRepository.get(filters)
        this.jokes = data || []
      } catch (e) {
        // keep UI stable on error
        this.jokes = []
        // log for debugging
        // eslint-disable-next-line no-console
        console.error(e)
      } finally {
        this.loading = false
      }
    }
  }
})
</script>

<template>
  <div class="bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 min-h-screen text-white">
    <div class="max-w-6xl mx-auto px-6 py-16">

      <!-- Header -->
      <div class="text-center mb-14">
        <h1 class="text-4xl md:text-5xl font-extrabold bg-gradient-to-r from-pink-400 via-purple-400 to-indigo-400 bg-clip-text text-transparent">
          Programming Jokes
        </h1>
        <p class="text-slate-300 mt-4">
          Because developers need debugging for life too.
        </p>
      </div>

      <div class="flex justify-center mb-10">
        <button
          @click="fetchJokes"
          class="px-6 py-3 bg-purple-600 hover:bg-purple-500 text-white font-semibold rounded-xl shadow-lg transition duration-300 disabled:opacity-50"
          :disabled="loading"
        >
          {{ loading ? "Loading..." : "Load Fresh Jokes" }}
        </button>
      </div>

      <!-- Cards -->
      <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
        <div
          v-for="joke in jokes"
          :key="joke.id"
          class="group relative bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl p-6 shadow-xl hover:shadow-purple-500/30 transition duration-500 hover:-translate-y-2"
        >
          <!-- ID -->
          <div class="absolute -top-4 -right-4 bg-gradient-to-r from-pink-500 to-purple-600 text-xs font-bold px-4 py-1 rounded-full shadow-lg">
            #{{ joke.id }}
          </div>

          <!-- Type -->
          <span class="inline-block text-xs uppercase tracking-widest text-purple-300 mb-4">
            {{ joke.type }}
          </span>

          <!-- Setup -->
          <h2 class="text-lg font-semibold leading-relaxed">
            {{ joke.setup }}
          </h2>

          <!-- Divider -->
          <div class="my-4 h-px bg-gradient-to-r from-transparent via-purple-400 to-transparent opacity-40"></div>

          <!-- Punchline -->
          <p class="text-purple-200 font-medium opacity-90 group-hover:opacity-100 transition">
            {{ joke.punchline }}
          </p>
        </div>
      </div>

    </div>
  </div>
</template>

<style scoped>

</style>
