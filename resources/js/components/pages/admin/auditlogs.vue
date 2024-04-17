<template>
    <div class="mx-5 mb-2 px-2.5 py-5 pb-2.5 bg-white rounded">
        <va-data-table
        id="data-table"
        :items="auditTrail"
        :columns="auds.tblColumns"
        :per-page="$root.config.tblPerPage"
        :current-page="$root.config.tblCurrPage"
        no-data-html="No audit log(s) to show"
        :filter="filter"
        @filtered="filtered = $event.items"
        animated
        itemSize="250px"
        class="w-full"
        >
            <template #headerAppend>
                <tr class="table-crud__slot">
                    <th
                    class="py-1 pr-1"
                    >
                        <va-input
                        v-model="filter"
                        placeholder="Search..."
                        class="w-full"
                        >
                            <template #appendInner>
                                <va-icon name="search" color="#C2C2C2" />
                            </template>
                        </va-input>
                    </th>
                </tr>
            </template>
            <template #cell(module)="{ value }">
                {{
                    auds.auditCategory[
                        $root.arrayFind(
                            auds.auditCategory, (item) => item.value === parseInt(value)
                        )
                    ].label
                }}
            </template>
            <template #cell(userID)="{ value }">
                <p v-if="value">{{ value }}</p>
                <p
                v-if="!value"
                class="
                    w-[132px] rounded-[2px] bg-[var(--va-primary)]
                    px-[5px] pt-[5px] pb-[4px]
                    text-[14px] text-white text-center
                "
                >
                    <pre>SYSTEM GENERATED</pre>
                </p>
            </template>
            <template #cell(action_type)="{ value }">
                <va-badge

                :text="
                    value &&
                    (
                        auds.auditAction[
                            $root.arrayFind(
                                auds.auditAction, (item) => item.value === parseInt(value)
                            )
                        ].label
                    )
                "
                :color="
                    value &&
                    (
                        auds.auditAction[
                            $root.arrayFind(
                                auds.auditAction, (item) => item.value === parseInt(value)
                            )
                        ].color
                    )
                "
                />
            </template>
            <template #cell(user_type)="{ value }">
                <va-badge 
                :text="auds.types[value].label"
                :color="auds.types[value].color"
                />
                <!-- {{ acc.types[value].label }} -->
            </template>
            <template #cell(created_at)="{ value }">
                {{ formatDate(value, 'MMM. Do YYYY', 'Invalid Date') }}
            </template>

            <template #bodyAppend>
                <tr v-if="$root.tblPagination(auditTrail)">
                    <td
                    id="pagination"
                    :colspan="auds.tblColumns.length"
                    >
                        <div class="flex pt-[10px] select-none">
                            <va-pagination
                            class="justify-center"
                            v-model="$root.config.tblCurrPage"
                            :pages="filter == '' ? $root.tblPagination(auditTrail) : (pages, $root.config.tblCurrPage = 1)"
                            input
                            />
                        </div>
                    </td>
                </tr>
            </template>
        </va-data-table>
    </div>
</template>

<style lang="scss" scoped>
.table-crud {
 

    .va-input {
        display: block;
    }

    &__slot {
        th {
            vertical-align: middle;
            width: max-content;
        }
    }
}
</style>

<script>
import formatDate from '@/functions/formatdate.js';

export default {
    data () {
        const auds = {
            tblColumns: [
                { key: "module", label: "Module", width: "20%", sortable: false },
                { key: "userID", label: "User ID", width: "20%", sortable: true },
                { key: "user_type", label: "Role", width: "20%", sortable: true },
                { key: "action_type", label: "Action", width: "20%", sortable: false },
                { key: "created_at", label: "Created On",width: "50%", thAlign: "start", tdVerticalAlign: "middle", sortable: true }
            ],
            auditCategory: [
                { label: "Account", value: 0 },
                { label: "Thesis", value: 1 },
                { label: "Authors", value: 2 },
                { label: "Keywords", value: 3 },
                { label: "Category", value: 4 },
                { label: "Database", value: 5 },
                
            ],
            auditAction: [
                { label: "Create", color: "success", value: 0 },
                { label: "Update", color: "warning", value: 1 },
                { label: "Delete", color: "danger", value: 2 },
                { label: "Enable", color: "primary", value: 3 },
                { label: "Disable", color: "info", value: 4 }
            ],
            types: [
                { label: "Administrator", value: 0, color: "warning" },
                { label: "AssistantLibrarian", value: 1, color: "primary" },
                { label: "Librarian", value: 2, color: "info"},
                { label: "Student", value: 3, color: "success" }
            ]
        };

        return {
            auds,
            auditTrail: [],
            activePreviewRow: null,
            filtered: null,
            filter: ""
        };
    },
    computed: {
        pages() {
            return this.$root.config.tblPerPage && this.$root.config.tblPerPage !== 0
            ? Math.ceil(this.filtered.length / this.$root.config.tblPerPage)
            : this.filtered.length;
        },
    },
    mounted () {
        this.getAuditTrail();
    },
    methods: {
        getAuditTrail() {
            axios({
                method: 'GET',
                type: 'JSON',
                url: '/audittrail/get'
            }).then(response => {
                if (response.data.status == 1) {
                    this.auditTrail = response.data.result;
                } else this.$root.prompt(response.data.text);
            }).catch(error => {
                this.$root.prompt(error.response.data.message);
            });
        },
        formatDate
    }
}
</script>
