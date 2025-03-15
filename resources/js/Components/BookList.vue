<script setup>
import { defineProps } from 'vue';

defineProps({
    books: Object
});

function truncateText(text) {
    const maxLength = 100;
    if (text && text.length > maxLength) {
      return text.substring(0, maxLength) + '...';
    }
    return text;
}
</script>

<template>
  <div class="row">
    <h2 class="my-4 col">Book list</h2>
    <div class="col text-end">
      <a class="my-4 btn btn-primary" href="/books/create">New</a>
    </div>
  </div>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
        <div v-for="book in books.data" :key="book.id" class="col">
          <div class="card shadow-sm">
            <img v-if="book.image_path" :src="book.image_path" alt="Book cover" style="width: 100%; height: 225px; object-fit: cover;">
            <svg v-else class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">
              <title>Placeholder</title>
              <rect width="100%" height="100%" fill="#55595c"></rect>
              <text x="50%" y="50%" fill="#eceeef" dy=".3em" text-anchor="middle">No photo</text>
            </svg>
            <div class="card-body">
              <h5>{{ book.title }}</h5>
              <p>
                Authors:
                <span v-if="book.authors && book.authors.length > 0">
                  <span v-for="(author, index) in book.authors" :key="author.id">
                    {{ author.first_name }} {{ author.name }}<span v-if="index < book.authors.length - 1">, </span>
                  </span>
                </span>
                <span v-else>none</span>
              </p>
              <p class="card-text">{{ truncateText(book.description) }}</p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary">View</button>
                </div>
                <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-success">Buy</button>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
    <div class="mt-4">
    <ul class="pagination">
        <li v-for="(link, index) in books.links" :key="index" class="page-item" :class="{ active: link.active, disabled: !link.url }">
            <a v-if="link.url" class="page-link" :href="link.url" v-html="link.label"></a>
            <span v-else class="page-link" v-html="link.label"></span>
        </li>
    </ul>
</div>
</template>