<template>
    <div class="container">
        <div class="row justify-content-center">

            <b-table
                id="my-table"
                :items="items"
                :fields="fields"
                :busy.sync="isBusy"
                :per-page="perPage"
                :current-page="currentPage"
            >
                <template slot="avatar" slot-scope="row">
                    <b-img :src="row.item.avatar" width=75 height=75 alt="Could not load image"></b-img>
                </template>

                <template slot="show_details" slot-scope="row">
                    <b-button size="sm" @click="row.toggleDetails" class="mr-2">
                    {{ row.detailsShowing ? 'Hide' : 'Show'}} Transactions
                    </b-button>
                </template>

                <template slot="row-details" slot-scope="row">
                    <b-table
                        id="my-table"
                        :items="row.item.transactions"
                        :fields="['id', 'amount', 'transaction_date']"
                    ></b-table>
                </template>
            </b-table>

            <b-pagination
                v-model="currentPage"
                :total-rows="rows"
                :per-page="perPage"
                aria-controls="my-table"
                @input="fetchClients(currentPage)"
            ></b-pagination>

        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                fields: ['id', 'first_name', 'last_name', 'email', 'created_at', 'avatar', 'show_details'],
                perPage: 0,
                currentPage: 1,
                items: [],
                rows: 0,
                isBusy: false,
            };
        },
        
        mounted(currentPage) {
            // this token should be set to localstorage after login, with the returned token, but as it's static for now and we're not using JWT, for ease of use, we hardcode it
            localStorage.setItem('token', 'secret');
            this.fetchClients(currentPage);
        },

        methods: {
            fetchClients(currentPage) {
                this.isBusy = true;
                axios.get(`api/clients?page=${currentPage}`, {
                    headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
                }).then((res) => {
                    this.items = res.data.data;
                    this.currentPage = res.data.meta.current_page;
                    this.perPage = res.data.meta.per_page;
                    this.rows = res.data.meta.total;
                    this.isBusy = false;
                });
            },
        }
    }
</script>