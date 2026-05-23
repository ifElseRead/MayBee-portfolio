<script setup>
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },

    description: {
        type: String,
        default: "",
    },

    url: {
        type: String,
        default: "",
    },

    image: {
        type: String,
        default: "/images/social-preview.svg",
    },

    type: {
        type: String,
        default: "website",
    },
});

const siteName = "Murfy.uk";

// Make image absolute if a relative path is supplied
const imageUrl = props.image.startsWith("http")
    ? props.image
    : `https://murfy.uk${props.image}`;
</script>

<template>
    <Head>
        <!-- Main SEO -->
        <title>{{ title }}</title>

        <meta name="description" :content="description" />

        <meta name="author" content="John" />

        <!-- Canonical -->
        <link v-if="url" rel="canonical" :href="url" />

        <!-- Open Graph -->
        <meta property="og:type" :content="type" />

        <meta property="og:site_name" :content="siteName" />

        <meta property="og:title" :content="title" />

        <meta property="og:description" :content="description" />

        <meta property="og:url" :content="url" />

        <meta property="og:image" :content="imageUrl" />

        <!-- Twitter/X -->
        <meta name="twitter:card" content="summary_large_image" />

        <meta name="twitter:title" :content="title" />

        <meta name="twitter:description" :content="description" />

        <meta name="twitter:image" :content="imageUrl" />
    </Head>
</template>
