<template>
    <div class="mx-5 mb-2 px-2.5 pb-2.5 bg-white rounded">
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
        >
            <template #headerAppend>
                <tr class="table-crud__slot">
                    <th
                    class="py-1 pr-1"
                    colspan="2"
                    :key="key"
                    >
                        <va-input
                        v-model="input"
                        placeholder="Search..."
                        >
                            <template #appendInner>
                                <va-icon name="search" color="#C2C2C2" />
                            </template>
                        </va-input>
                    </th>
                    <th
                    v-for="key in Object.keys(auds.tblColumns).slice(0, 3)"
                    :key="key"
                    class="py-1 pr-1"
                    >
                        <va-button
                        v-if="key.includes(5)"
                        class="invisible"
                        preset="primary"
                        disabled
                        />
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
            <template #cell(user_type)="{ value }">
                <va-badge 
                :text="auds.types[value].label"
                :color="auds.types[value].color"
                />
                <!-- {{ acc.types[value].label }} -->
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
            <template #cell(action_description)="{ rowData }">
                <div
                id="table-row-desc"
                >
                    <va-input
                    type="textarea"
                    :model-value="rowData.action_description"
                    placeholder="No description available"
                    readonly
                    autosize
                    outline
                    />
                </div>
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
                { key: "created_at", label: "Created On", width: "50%", thAlign: "start", tdVerticalAlign: "middle", sortable: true },
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
                { label: "Disable", color: "info", value: 4 },
            ],
            types: [
                { label: "Administrator", value: 0, color: "warning" },
                { label: "AssistantLibrarian", value: 1, color: "primary" },
                { label: "Librarian", value: 2, color: "info" },
                { label: "Student", value: 3, color: "success" },
            ],
        };

        return {
            auds,
            auditTrail: [],
            activePreviewRow: null,
            filtered: null,
            input: "",
            filter: this.input,
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
        updateFilter(filter) {
            this.filter = filter;
        },
        formatDate
    },
    watch: {
        input(newValue) {
            this.updateFilter(newValue);
        }
    }

}
</script>
