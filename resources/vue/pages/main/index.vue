<template>
    <div>
        <!-- <v-parallax height="500" src="/img/parallax5.png" class="mb-5">
            <v-container>
                <v-row no-gutters>
                    <v-col cols="4"></v-col>
                    <v-col cols="4" align-self="center">
                        <g-search></g-search>
                    </v-col>
                </v-row>
            </v-container>
        </v-parallax> -->
        <v-carousel hide-delimiters cycle>
            <v-carousel-item
                v-for="(image, i) in images"
                :key="i"
                :src="image"
            ></v-carousel-item>
        </v-carousel>
        <g-quantity
            v-if="addToCartDialog"
            :dialog="addToCartDialog"
            v-model="selectedItem"
            @close="closeDialog"
            @add="add"
            @update="closeDialog"
            :update-mode="update"
        ></g-quantity>
        <v-lazy
            v-model="isActive"
            :options="{
                threshold: 0.5,
            }"
            min-height="200"
            transition="fade-transition"
        >
            <div>
                <v-container>
                    <v-row align="center" justify="center">
                        <v-col align-self="align" cols="9">
                            <v-card color="background" elevation="0">
                                <v-data-iterator
                                    hide-default-footer
                                    :items="items"
                                >
                                    <v-row>
                                        <template v-if="loadingItems">
                                            <v-col
                                                cols="2"
                                                v-for="index in 6"
                                                :key="index"
                                            >
                                                <v-skeleton-loader
                                                    class="mx-auto"
                                                    max-width="200"
                                                    type="card"
                                                ></v-skeleton-loader>
                                            </v-col>
                                        </template>
                                        <template v-else>
                                            <v-col
                                                v-for="(item, index) in items"
                                                :key="index"
                                                cols="2"
                                            >
                                                <v-card
                                                    height="100%"
                                                    width="200"
                                                    @click="addToCart(item)"
                                                >
                                                    <v-card-text>
                                                        <v-img
                                                            max-height="100"
                                                            :src="
                                                                '/img/' +
                                                                item.image
                                                            "
                                                        ></v-img>
                                                    </v-card-text>
                                                    <v-card-text>
                                                        <div class="text-left">
                                                            {{ item.name }}
                                                        </div>
                                                    </v-card-text>
                                                    <v-divider></v-divider>
                                                    <v-card-text>
                                                        <div
                                                            class="text-left font-weight-bold title"
                                                        >
                                                            PHP
                                                            {{ item.price }}
                                                        </div>
                                                    </v-card-text>
                                                </v-card>
                                            </v-col>
                                        </template>
                                    </v-row>
                                </v-data-iterator>
                            </v-card>
                            <div class="text-center mt-4">
                                <v-pagination
                                    color="secondary"
                                    v-model="page"
                                    :length="length"
                                    @next="refresh"
                                    @previous="refresh"
                                    :total-visible="6"
                                ></v-pagination>
                            </div>
                        </v-col>
                    </v-row>
                </v-container>
            </div>
        </v-lazy>
    </div>
</template>
<route-meta>{ "breadcrumb": "Home"}</route-meta>
<script>
export default {
    data() {
        return {
            page: 1,
            isActive: false,
            items: [],
            length: 0,
            loadingItems: true,
            addToCartDialog: false,
            update: false,
            selectedItem: {},
            images: ["/img/topbg.jpg", "/img/sckss.jpg", "/img/gmash.jpg"],
        };
    },
    async created() {
        let vm = this;
        vm.refresh();
    },
    methods: {
        async refresh() {
            let vm = this;
            vm.loadingItems = true;
            setTimeout(async () => {
                const { data } = await axios.get(
                    `/guest/items?page=${vm.page}&itemsPerPage=6&mustSort=false&multiSort=false`
                );
                vm.items = data.data;
                vm.length = data.last_page;
                vm.loadingItems = false;
            }, 400);
        },
        addToCart(value) {
            let vm = this;
            if (this.$ls.get("cart")) {
                vm.$ls.get("cart").forEach((item) => {
                    if (item.id == value.id) {
                        vm.update = true;
                    }
                });
            }
            vm.addToCartDialog = true;
            vm.selectedItem = value;
        },
        closeDialog() {
            let vm = this;
            vm.addToCartDialog = false;
            vm.update = false;
            vm.selectedItem = {};
        },
        add(value) {
            let vm = this;
            let cart;
            if (vm.$ls.get("cart")) {
                cart = vm.$ls.get("cart");
            } else {
                cart = [];
            }
            cart.push(value);
            vm.$ls.set("cart", cart);
            if (vm.$ls.get("cart")) {
                cart = vm.$ls.get("cart");
                vm.setCart(cart.length);
            }
            vm.update = false;
            vm.addToCartDialog = false;
            vm.selectedItem = {};
        },
    },
};
</script>
<style lang=""></style>
