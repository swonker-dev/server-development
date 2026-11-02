<template>
    <aside v-if="article" class="article-notification" role="status" aria-live="polite">
        <button
            class="article-notification-close"
            type="button"
            aria-label="Закрыть уведомление"
            @click="article = null"
        >×</button>
        <span class="kicker">Только что опубликовано</span>
        <h2>Новая история</h2>
        <a class="article-notification-title" :href="`/articles/${article.id}`">{{ article.title }}</a>
        <p>На night notes. появилась новая статья.</p>
    </aside>
</template>

<script>
export default {
    data() {
        return { article: null };
    },

    created() {
        window.Echo?.channel('articles').listen('NewArticleEvent', (event) => {
            if (Number.isInteger(event.article?.id) && typeof event.article.title === 'string') {
                this.article = event.article;
            }
        });
    },

    beforeUnmount() {
        window.Echo?.leaveChannel('articles');
    },
};
</script>
