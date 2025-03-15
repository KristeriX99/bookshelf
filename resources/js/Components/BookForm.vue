<script setup>
import axios from 'axios';
import { reactive, ref } from 'vue';

defineProps({
    authors: Array
});
 
const errors = ref({})
 
const form = reactive({
    title: '',
    description: '',
    published: '',
    image: '',
    authors: []
});
 
function submit() {

    const formData = new FormData();
    formData.append('title', form.title);
    formData.append('description', form.description);
    formData.append('published', form.published);
    formData.append('image', form.image);
    form.authors.forEach(author => {
        formData.append('authors[]', author);
    });

    axios.post('/books', formData)
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
    form.image = e.target.files[0];
}
</script>
 
<template>
    <h2 class="my-4">Add a book</h2>
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

                <div>
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

                <div class="mb-3">
                    <label for="image">Cover image</label>
                    <input type="file" class="form-control" id="image" @input="imageUpload">
                    <div class="text-danger" v-if="errors?.image">{{ errors.image[0] }}</div>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</template>