<template>
    <div class="px-10 max-sm:p-0 text-black transform-none h-full">
        <div
            v-if="thesis"
            class="w-2/3 mx-auto max-sm:w-full justify-center h-full px-5 text-black shadow-xl overflow-y-hidden"
        >
            <div class="flex-start bg-inherit text-black h-fit justify-center">
                <VaButton
                    class="w-fit text-black my-5 hover:bg-blue-200"
                    preset="secondary"
                    icon="arrow_back_ios_new"
                    icon-color="#000000"
                    @click="$emit('home')"
                    size="large"
                >
                    <span class="text-black">Back</span>
                </VaButton>
            </div>
            <div class="w-full flex-wrap py-10 px-5 h-full text-wraps">
                <div class="">
                    <h1 class="py-1 text-2xl text-center">
                        {{ thesis.title }}
                    </h1>
                </div>
                <div>
                    <p class="pb-5 text-lg text-center">
                        {{
                            formatDate(
                                thesis.published_at,
                                "MMMM do, YYYY",
                                "Invalid Date"
                            )
                        }}
                    </p>
                </div>
                <div>
                    <p class="text-lg text-justify flex-wrap py-3 whitespace-pre-line">
                        {{ thesis.abstract }}
                    </p>
                </div>

                <div class="text-black">
                    <VaList class="text-black">
                        <label class="text-lg font-bold" for="authors">Authors</label>
                        <ul class="text-black text-lg list-inside list-disc">
                            <li v-for="author in thesis.authors">
                                {{ author.name }}
                            </li>
                        </ul>
                    </VaList>
                    <VaList class="text-black">
                        <label class="text-black text-lg py-5 font-bold" for="category"
                            >Category</label
                        >
                        <ul class="text-black text-lg list-disc list-inside">
                            <VaChip
                                v-for="category in thesis.categories"
                                color="primary"
                                class="mr-2 mb-2"
                                size="medium"
                                square
                            >
                                {{ category.category }}
                            </VaChip>
                        </ul>
                    </VaList>
                    <div class="text-black">
                        <label class="text-lg py-5 font-bold" for="category">Tags</label>
                        <ul class="text-black text-lg list-disc list-inside">
                            <VaChip
                            square
                            v-for="keyword in thesis.keywords"
                            color="info"
                            class="mr-2 mb-2"
                            size="medium"
                            >
                            <span class="text-black">{{ keyword.keyword }}</span>
                               
                            </VaChip>
                        </ul>
                    </div>
                </div>
                <div class="downloads py-5">
                    <div class="flex gap-2 max-sm:flex-col">
                        <VaButton
                        color="#2756f2"
                        gradient
                        text-color="warning"
                        :disabled="
                            thesis.pdf === '' || thesis.pdf === null
                                ? true
                                : false
                        "
                        icon-right="arrow_forward"
                        icon-color="warning"
                        @click="openPdf()"
                        >
                            {{
                                thesis.pdf === "" || thesis.pdf === null
                                    ? "No PDF attached"
                                    : "PDF"
                            }}
                        </VaButton>
                        <VaButton
                        gradient
                        color="#2756f2"
                        text-color="warning"
                        :disabled="
                            thesis.video === '' || thesis.video === null
                                ? true
                                : false
                        "
                        icon-right="arrow_forward"
                        icon-color="warning"
                        @click="openVideo()"
                        >
                            {{
                                thesis.video === "" || thesis.video === null
                                    ? "No Video attached"
                                    : "Video"
                            }}
                        </VaButton>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <div class="m-10">
                <div class="flex flex-center justify-center mx-auto">
                    <p class="text-3xl flex justify-center font-serif">
                        Something went wrong! Please go back!
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- pdf -->
    <VaModal
        v-model="isPdfOpen"
        hide-default-actions
        size="large"
        class="w-full"
        close-button
    >
        <div class="w-full h-screen">
            <iframe :src="thesis.pdf" class="w-full h-full"></iframe>
        </div>
    </VaModal>
    <!-- video -->
    <VaModal v-model="isVideoOpen" hide-default-actions class="w-full">
        <div class="w-full h-fit">
            <video class="w-fit h-fit" controls>
                <source :src="thesis.video" type="video/mp4" />
            </video>
        </div>
    </VaModal>
</template>

<script>
import formatDate from "@/functions/formatdate.js";
export default {
    data() {
        return {
            thesis: {},
            selectedID: this.selectedThesisId,
            isVideoOpen: false,
            isPdfOpen: false,
        };
    },
    props: ["selectedThesisId"],
    mounted() {
        this.getThesis();
    },
    methods: {
        openPdf() {
            this.isPdfOpen = true;
        },
        openVideo() {
            this.isVideoOpen = true;
        },
        getThesis() {
            axios({
                method: "GET",
                type: "JSON",
                url: "/thesisq/" + this.selectedID,
            })
                .then((response) => {
                    if (response.data.status == 1) {
                        this.thesis = response.data.result;
                        console.log(this.thesis);
                    } else this.$root.prompt(response.data.text);
                })
                .catch((error) => {
                    this.$root.prompt(error.response.data.message);
                });
        },
        formatDate,
    },
};
</script>
