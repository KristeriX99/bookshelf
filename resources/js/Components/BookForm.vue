<script setup>
import axios from 'axios';
import { reactive, ref, onMounted } from 'vue';

const props = defineProps({
    authors: Array,
    book: Object
});
 
const errors = ref({});
const existingImage = ref('');
 
const form = reactive({
    title: '',
    description: '',
    published: '',
    image: '',
    authors: []
});

onMounted(() => {
    if (props.book) {
        form.title = props.book.title;
        form.description = props.book.description ?? '';
        form.published = props.book.published;
        form.authors = props.book.authors.map(author => author.id);
        existingImage.value = props.book.image_path;
    }
})
 
function submit() {

    const formData = new FormData();
    formData.append('title', form.title);
    formData.append('description', form.description);
    formData.append('published', form.published);
    formData.append('image', form.image);
    form.authors.forEach(author => {
        formData.append('authors[]', author);
    });

    const url = props.book ? `/books/${props.book.id}` : '/books';
    const method = props.book ? 'put' : 'post';

    // simulate method
    if (props.book) {
        formData.append('_method', method);
    }

    axios.post(url, formData)
        .then(response => {
            window.location.href = response.request.responseURL;
        })
        .catch(error => {
            if (error.response.status === 422) {
                errors.value = error.response.data.errors
            }
        })
}

function imageUpload(e) {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        existingImage.value = URL.createObjectURL(file);
    }
}

function formatDate(dateString) {

    const date = new Date(dateString);
            return date.toLocaleDateString('lv-LV', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            }).replace(',', ''); // Removes comma between date and time
}
</script>
 
<template>
    
    <div class="row">
        <h2 class="my-4 col">
            <span v-if="book">Edit book</span>
            <span v-else>Add a book</span>
        </h2>
        <div class="col text-end">
        <a class="my-4 btn btn-secondary" href="/books">To list</a>
        </div>
    </div>
    <div class="bg-gray-50 min-h-screen pt-12">
        <div class="max-w-md mx-auto rounded shadow-sm p-6 text-gray-900">
            <form @submit.prevent="submit" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" v-model="form.title">
                    <div class="text-danger" v-if="errors?.title">{{ errors.title[0] }}</div>
                </div>

                <div class="mb-3">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" rows="3" v-model="form.description"></textarea>
                    <div class="text-danger" v-if="errors?.description">{{ errors.description[0] }}</div>
                </div>

                <div class="mb-3">
                    <label for="description">Authors</label>
                    <select v-model="form.authors" class="form-select" multiple>
                        <option v-for="author in authors" :key="author.id" :value="author.id">
                            {{ author.first_name }} {{ author.name }}
                        </option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="published">Published</label>
                    <input type="date" class="form-control" id="published" v-model="form.published">
                    <div class="text-danger" v-if="errors?.published">{{ errors.published[0] }}</div>
                </div>

                <div class="mb-3 mt-2">
                    <label for="image">Cover image</label>
                    <input type="file" class="form-control" id="image" @input="imageUpload">
                    <div class="text-danger" v-if="errors?.image">{{ errors.image[0] }}</div>
                    <div v-if="existingImage" class="mb-2">
                        <img :src="existingImage" alt="Current Book Cover" class="img-thumbnail" width="150">
                    </div>
                </div>

                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary my-3">Submit</button>
                </div>
            </form>

            <ol v-if="book" class="mt-3">
                <h5>Bought books:</h5>
                <li v-for="(sales, index) in book.sales.slice().reverse()" :key="sales.id">
                    {{ book.sales.length - index }}. {{ formatDate(sales.created_at) }}
                </li>
            </ol>
        </div>
    </div>
</template>