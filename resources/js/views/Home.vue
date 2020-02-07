<template>
    <div class="container">
        <div class="columns">

            <main class="column" role="main">
                <div class="card">
                  <header class="card-header">
                    <p class="card-header-title">
                      Home
                    </p>
                  </header>
                  <div class="card-content">
                    <div class="content">
                      Página inicial

                      <div
                        v-for="post in posts"
                        :key="post.id"
                      >
                        {{ post.title }}
                      </div>

                    </div>
                  </div>
                </div>
            </main>
        </div>
    </div>    
</template>

<script>
    import axios from 'axios';

    export default {
        data () {
          return {
            posts: []
          }
        },

        methods: {
          async loadPosts () {
            let response = await axios.get('posts')

            this.posts = response.data
          }
        },

        mounted () {
          this.loadPosts()
        }
  };
</script>
