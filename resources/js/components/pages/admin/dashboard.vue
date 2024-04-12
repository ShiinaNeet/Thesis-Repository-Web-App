<template>
    <navigation/>
    <div class="flex items-start min-h-[calc(100vh-62px)]">
        <div
        style="position: sticky; top:62px;"
        class="flex py-8 min-h-[calc(100vh-62px)]"
        :class="
            menu_open
            ? ('min-w-[150px] max-w-[150px]')
            : ('pr-1 width-[2.775rem] max-w-[2.775rem]')
        ">
            <div
            id="settings-nav-wrapper"
            :class="
                menu_open
                ? ('min-w-[150px] max-w-[150px]')
                : ('width-[2.775rem] max-w-[2.775rem] overflow-x-hidden')
            ">
                <div class="relative">
                    <h5
                    class="pl-[30px] pt-[5.5px] pb-[3px] text-base text-center"
                    :class="!menu_open && ('invisible')"
                    >
                        Management
                    </h5>
                    <div class="absolute top-0 left-[0.25rem]">
                        <va-button
                        :title="menu_open ? 'Hide menu' : 'Open menu'"
                        preset="primary"
                        :icon="menu_open ? 'menu_open' : 'menu'"
                        @click="menu_open = !menu_open"
                        />
                    </div>
                </div>
                <va-divider class="pr-1" />

                <div
                v-if="$root.auth.userType == 2 || $root.auth.userType == 0 "
                >
                    <div
                    v-if="!menu_open"
                    class="va-title mx-3 my-2"
                    >
                        <va-icon
                        name="dashboard"
                        size="small"
                        title="Dashboard"
                        class="ml-[2px] my-[3px]"
                        :class="menu_open && ('mr-1')"
                        />
                    </div>
                    <va-sidebar-item
                    v-if="menu_open"
                    v-for="(setting, idx) in dashboard"
                    :key="idx"
                    :active="isSettingActive(setting)"
                    @click="setSettingActive(setting)"
                    >
                        <va-sidebar-item-content class="min-h-[20px!important] p-[7px!important]">
                            <va-sidebar-item-title class="text-sm select-none">
                                {{ setting }}
                            </va-sidebar-item-title>
                        </va-sidebar-item-content>
                    </va-sidebar-item>
                    <va-divider class="pr-1" />
                </div>
                <div
                v-if="$root.auth.userType == 2 || $root.auth.userType == 0 "
                >
                    <div 
                    class="va-title mx-3 my-2"
                    >
                        <va-icon
                        name="tag"
                        size="small"
                        title="Maintenance"
                        class="ml-[2px] my-[3px]"
                        :class="menu_open && ('mr-1')"
                        />
                        <span
                        class="align-middle tracking-wider"
                        :class="!menu_open && ('hidden')"
                        >
                            Accounts
                        </span>
                    </div>
                    <va-sidebar-item
                    v-if="menu_open"
                    v-for="(setting, idx) in account_mngt.filter(item => {
                        return item;
                    })"
                    :key="idx"
                    :active="isSettingActive(setting)"
                    @click="setSettingActive(setting), menu_open = !menu_open"
                    >
                        <va-sidebar-item-content class="min-h-[20px!important] p-[7px!important]">
                            <va-sidebar-item-title class="text-sm select-none">
                                {{ setting }}
                            </va-sidebar-item-title>
                        </va-sidebar-item-content>
                    </va-sidebar-item>
                    <va-divider class="pr-1" />
                </div>

                <div>
                    <div class="va-title mx-3 my-2">
                        <va-icon
                        name="leaderboard"
                        size="small"
                        title="Reports"
                        class="ml-[2px] my-[3px]"
                        :class="menu_open && ('mr-1')"
                        />
                        <span
                        class="align-middle tracking-wider"
                        :class="!menu_open && ('hidden')"
                        >
                            Thesis
                        </span>
                    </div>
                    <va-sidebar-item
                    v-if="menu_open"
                    v-for="(setting, idx) in thesis_mngt.filter(item => {
                        return true
                    })"
                    :key="idx"
                    :active="isSettingActive(setting)"
                    @click="setSettingActive(setting), setting === 'Thesis' ? menu_open = !menu_open : ''"
                    >
                        <va-sidebar-item-content class="min-h-[20px!important] p-[7px!important]">
                            <va-sidebar-item-title class="text-sm select-none">
                                {{ setting }}
                            </va-sidebar-item-title>
                        </va-sidebar-item-content>
                    </va-sidebar-item>
                    <va-divider class="pr-1" />
                </div>
                <div
                v-if="$root.auth.userType !== 1 && $root.auth.userType !== 3"
                >
                    <div class="va-title mx-3 my-2">
                        <va-icon
                        name="storage"
                        size="small"
                        title="database"
                        class="ml-[2px] my-[3px]"
                        :class="menu_open && ('mr-1')"
                        />
                        <span
                        class="align-middle tracking-wider"
                        :class="!menu_open && ('hidden')"
                        >
                            System
                        </span>
                    </div>
                    <va-sidebar-item
                    v-if="menu_open"
                    v-for="(setting, idx) in system_mngt.filter(item => {
                        return true
                    })"
                    :key="idx"
                    :active="isSettingActive(setting)"
                    @click="setSettingActive(setting), setting === 'Backup and Restore' ? menu_open = !menu_open : ''"
                    >
                        <va-sidebar-item-content class="min-h-[20px!important] p-[7px!important]">
                            <va-sidebar-item-title class="text-sm select-none">
                                {{ setting }}
                            </va-sidebar-item-title>
                        </va-sidebar-item-content>
                    </va-sidebar-item>
                    <va-divider class="pr-1" />
                </div>
            </div>
        </div>
        <div class="flex w-full p-0 min-h-[calc(100vh-62px)] overflow-x-hidden">
            <div
            id="settings-wrapper"
            >
                <template v-if="activeSetting === 'Dashboard'">
                    <home_tbl />
                </template>
                <template v-if="activeSetting === 'Accounts'">
                    <accounts_tbl />
                </template>
                <template v-if="activeSetting === 'Keyword'">
                    <keywords_tbl />
                </template>
                <template v-if="activeSetting === 'Author'">
                    <authors_tble />
                </template>
                <template v-if="activeSetting === 'Category'">
                    <category_tble />
                </template>
                <template v-if="activeSetting === 'Thesis'">
                    <thesis_tbl />
                </template>
                <template v-if="activeSetting === 'Backup and Restore'">
                    <backup_tbl />
                </template>
            </div>
        </div>
    </div>
</template>

<script>
import navbar from '../../navbar.vue';
import authorModule from './author.vue';
import categoryModule from './category.vue';
import accountsModule from './accounts.vue';
import keywordsModule from './keyword.vue';
import thesisModule from './thesis.vue';
import homeModule from './home.vue';
import backupModule from './backup.vue';
export default {
    data () {
        return {
            dashboard: ['Dashboard'],
            account_mngt: ['Accounts'],
            thesis_mngt: ['Thesis','Keyword','Category','Author'],
            system_mngt: ['Backup and Restore'],
            activeSetting: 'Dashboard',
            menu_open: true,
        };
    },
    components: {
        navigation: navbar,
        accounts_tbl: accountsModule,
        keywords_tbl: keywordsModule,
        authors_tble: authorModule,
        category_tble: categoryModule,
        thesis_tbl: thesisModule,
        home_tbl: homeModule,
        backup_tbl: backupModule,

    },
    
    methods: {
        isSettingActive(setting) {
            return this.activeSetting === setting;
        },
        setSettingActive(setting) {
            this.$root.config.tblCurrPage = 1;
            this.activeSetting = setting;
        },
    }
}
</script>