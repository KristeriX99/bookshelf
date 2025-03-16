<script setup>
import { ref, defineProps } from 'vue';
import {useToast} from 'vue-toast-notification';

const props = defineProps({ 
    books: Object,
    search: {
        type: String,
        default: ''
    }
});
const toast = useToast();

const search = ref(props.search || '');

// bind to property so it can be updated dinamically
const booksData = ref(props.books);

function fetchSearchResults() {

    const url = new URL(window.location);
    url.searchParams.set('search', search.value);
    window.location.href = url.toString();
}

function truncateText(text) {
    const maxLength = 100;
    if (text && text.length > maxLength) {
      return text.substring(0, maxLength) + '...';
    }
    return text;
}

function buyBook(bookId) {
  axios.post(`/books/${bookId}/buy`)
    .then(response => {
      const bookIndex = booksData.value.data.findIndex(book => book.id === bookId);
      
      if (bookIndex !== -1) {
        booksData.value.data[bookIndex].sales_count = response.data.sales_count;
        booksData.value.data[bookIndex].monthly_sales = response.data.monthly_sales;

        toast.success('Book has been bought!');
      }
    })
    .catch(error => {
      console.error('Error buying book', error);
    });
}
</script>

<template>
  <div class="row">
    <h2 class="my-4 col">Book list</h2>
    <div class="col text-end">
      <a class="my-4 btn btn-primary" href="/books/create">New</a>
    </div>
  </div>
  <div class="row mb-2">
    <div class="col-md-4 offset-md-8">
  <div class="input-group">
    <input
      class="form-control"
      type="text"
      placeholder="Search by book title or author"
      v-model="search">
    <button class="btn btn-secondary" type="button" @click="fetchSearchResults">Search</button>
  </div>
</div>
  </div>
    <div v-if="booksData.data.length === 0" class="col text-center my-4">
      <p>No books to show</p>
  </div>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
        <div v-for="book in booksData.data" :key="book.id" class="col">
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
                  <a class="btn btn-sm btn-outline-secondary" :href="`/books/${book.id}/edit`">View/Edit</a>
                </div>
                <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-success" @click="buyBook(book.id)">Buy</button>
                </div>
              </div>
              <div class="text-muted lh-sm text-end mt-2">Sales this month: {{ book.monthly_sales }}</div>
              <div class="text-muted lh-sm text-end mt-2">Total sales: {{ book.sales_count }}</div>
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