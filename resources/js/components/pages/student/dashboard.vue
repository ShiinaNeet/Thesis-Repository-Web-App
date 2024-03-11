<template>
    <navigation/>
    <div class="flex items-start min-h-[calc(100vh-62px)]">
        <div class="flex w-full p-0 min-h-[calc(100vh-62px)] overflow-x-hidden bg-white-500">
            <div
            id="settings-wrapper"
            >
                <template v-if="activeSetting === 'Dashboard'">
                    <home_tbl @thesisSelected="handleThesisSelected" />
                </template>
                <template v-if="activeSetting === 'ThesisView'">
                    <thesis_tbl :selectedThesisId="selectedThesisId"/>
                </template>
                
            </div>
        </div>
    </div>
    
</template>

<script>
import navbar from '../../navbar.vue';
import home from './home.vue';
import thesisview from './thesisview.vue';

export default {
    data () {
        return {
            dashboard: ['Dashboard'],
            account_mngt: ['Accounts'],
            thesis_mngt: ['Thesis','Keyword','Category','Author'],
            activeSetting: 'Dashboard',
            menu_open: true,
            selectedThesisId: null,
        };
    },
    components: {
        navigation: navbar,
        home_tbl:home,
        thesis_tbl:thesisview,
    },
    
    methods: {
        isSettingActive(setting) {
            return this.activeSetting === setting;
        },
        setSettingActive(setting) {
            this.$root.config.tblCurrPage = 1;
            this.activeSetting = setting;
        },
        handleThesisSelected(id) {
            this.selectedThesisId = id;
          
            this.activeSetting = 'ThesisView';
        },
    }
}
</script>