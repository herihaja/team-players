<script>
export default {
    name: "pagination",
    props: ['currentPage', 'lastPage', 'total'],
    methods: {
        getPaginationLink: (page) => {
            return `/player/json/?page=${page}`;
        },
        range: (start, end) => {
            return Array.from({ length: end - start + 1 }, (_, index) => index + start);
        },
        onChildClick (event) {
            const link = event.target.getAttribute('data-link');
            this.$emit('clickedPageLink', {'link': link, 'event': event});
        }
    },
}
</script>
<template>
    <nav v-if="lastPage >= 1">
        <ul class="pagination justify-content-center">
            <li :class="['page-item', currentPage <= 1 ? 'disabled' : '']">
                <a class="page-link" href="javascript:void(0);" :data-link="getPaginationLink(currentPage - 1)" aria-label="Previous" :onClick="onChildClick" >
                    &laquo; 
                </a>
            </li>
            <li v-for="page in range(1, lastPage)" :key="page" :class="['page-item', page === currentPage ? 'active' : '']" >
                <a class="page-link" href="javascript:void(0);" :data-link="getPaginationLink(page)" :onClick="onChildClick">{{ page }}</a>
            </li>
            <li :class="['page-item', currentPage >= lastPage ? 'disabled' : '']">
                <a class="page-link" href="javascript:void(0);" :data-link="getPaginationLink(currentPage + 1)" aria-label="Next" :onClick="onChildClick">
                    &raquo;
                </a>
            </li>
        </ul>
    </nav>
</template>