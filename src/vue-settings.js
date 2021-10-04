import Vue from 'vue';
import CoreuiVue from '@coreui/vue';
import vSelect from 'vue-select';

import { cilPencil, cilSettings, cilInfo, cibGoogleKeep } from '@coreui/icons';
Vue.use(CoreuiVue);
Vue.component('v-select', vSelect);
import '@coreui/coreui/dist/css/coreui.min.css';
import 'vue-select/dist/vue-select.css';

const j = jQuery.noConflict();

var gen = new Vue({
    el: '#app',
    data(){
        return {
            domain:'',
            generate:null,
            search:null,
            affiliate_disclosure:null,
            is_adult:null,
            privacy:null,
            page_options: page_option,
            privacy_page: '',
        }
    },
    methods: {   

        setValues: function () {
            this.generate =  this.$refs.hasOwnProperty('generate')? this.$refs.generate.checked : null; 
            this.search =  this.$refs.hasOwnProperty('search')? this.$refs.search.checked : null; 
            this.affiliate_disclosure =this.$refs.hasOwnProperty('affiliate_disclosure')? this.$refs.affiliate_disclosure.checked:null;  
            this.is_adult = this.$refs.hasOwnProperty('is_adult')?this.$refs.is_adult.checked:null; 
            this.privacy =this.$refs.hasOwnProperty('privacy')? this.$refs.privacy.checked:null;    
            this.privacy_page = this.$refs.hasOwnProperty('privacy_page_mount') && this.$refs.privacy_page_mount.value ? this.$refs.privacy_page_mount.value : '';
        },
        onChangeCredit(){
            this.generate= !this.generate;
            this.$refs.generate.value = this.generate ? '1' : '0';
            console.log(this.generate);
        },
        onChangeSearch(){
            this.search= !this.search;
            this.$refs.search.value = this.search ? '1' : '0';
            console.log(this.search);
        },
        onChangeAffiliate(){
            this.affiliate_disclosure= !this.affiliate_disclosure;
            this.$refs.affiliate_disclosure.value = this.affiliate_disclosure ? '1' : '0';
            console.log(this.affiliate_disclosure);
        },
        onChangeIsAdult(){
            this.is_adult= !this.is_adult;
            this.$refs.is_adult.value = this.is_adult ? '1' : '0';
            console.log(this.is_adult);
        },
        onChangePrivacy(){
            this.privacy= !this.privacy;
            this.$refs.privacy.value = this.privacy ? '1' : '0';
            console.log(this.privacy);
        }    
    },
    mounted(){
        this.setValues();
        j('#testing').css('display','none');
    },
    icons: { cilPencil, cilSettings, cilInfo, cibGoogleKeep }
    
})