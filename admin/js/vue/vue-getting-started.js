$ = jQuery.noConflict();

Vue.component('HeaderSection', {
    render(createElement) {
        return createElement('header', {
            staticClass: 'wplegal-header-section'
        }, [createElement('h1',{
            staticClass: "wplegal-header-logo"
        },[createElement('div',{
            staticClass: "wplegal-logo"
        },[createElement('div',{
            staticClass: "wplegal-bg-img"
        })])])]);
    }
});

Vue.component('WelcomeSection', {
    render(createElement) {
        return createElement('div', {
            staticClass: 'wplegal-welcome-section'
        }, [createElement('div', {
            staticClass: 'wplegal-section-title'
        }, [createElement('p', {
            staticClass: 'wplegal-title-heading',
            domProps: {
                textContent: 'Welcome to WP Legal Pages!'
            }
        }), createElement('p', {
            staticClass: 'wplegal-title-subheading',
            domProps: {
                textContent: 'Simple one-click legal page management plugin.'
            }
        })]),
        createElement('div', {
            staticClass: 'wplegal-section-content'
        }, [createElement('p', {
            domProps: {
                innerHTML: 'Thank you for choosing WP Legal Pages plugin - the most powerful legal page management plugin.'
            }
        })])]);
    }
});

Vue.component('VideoSection', {
    render(createElement) {
        return createElement('div', {
            staticClass: 'wplegal-video-section'
        }, [createElement('iframe', {
            attrs: {
                width: '746',
                height: '350',
                src: obj.video_url
            },
        })]);
    }
});

Vue.component('TermsSection', {
    data: function() {
        return {
            accept_terms: {
                checked: !1
            }
        }
    },
    methods: {
        labelClass: function() {
            var s = '';
            if(this.isChecked()) {
                s += 'wplegal-styled-checkbox-label-checked'
            }
            if(this.isDisabled()) {
                s += ' wplegal-styled-checkbox-label-disabled';
            }
            return s;
        },
        titleClass: function() {
            var s = 'wplegal-styled-checkbox';
            if(this.isChecked()) {
                s += ' wplegal-styled-checkbox-checked';
            }
            return s;
        },
        isDisabled: function() {
            if(this.$parent.disabled) {
                return true;
            }
            return false;
        },
        isChecked: function() {
            if(this.accept_terms.checked) {
                return true;
            }
            return false;
        },
        updateSettings: function() {
            this.accept_terms.checked = !this.accept_terms.checked;
        },
        handleSubmit: function() {
            var data = {};
            $('.wplegal-input-checkbox input[type="checkbox"]').each(function(){
                if(this.checked) {
                    data[this.name] = this.value;
                }
            });
            $.post(obj.ajax_url,{'action':'save_accept_terms','data':data,'nonce':obj.ajax_nonce}).then((response => {
                if(response.success) {
                    this.$parent.disabled = 1;
                }
            }))

        }
    },
    mounted: function(){
        $.get(obj.ajax_url,{'action':'get_accept_terms','nonce':obj.ajax_nonce}).then((response => {
            if(response.success) {
                if(response.data == '1') {
                    this.$parent.disabled = 1;
                    this.accept_terms.checked = 1;
                }

            }
        }))
    },
    render(createElement) {
        var self = this;
        return createElement('div', {
            staticClass: 'wplegal-terms-section'
        }, [createElement('div', {
            staticClass: 'wplegal-section-content'
        }, [createElement('p', {
            domProps: {
                innerHTML: 'WP Legal Pages is a privacy policy and terms & conditions generator for WordPress. With just a few clicks you can generate <a href="https://club.wpeka.com/product/wplegalpages/?utm_source=wplegalpages&utm_medium=getting-started&utm_campaign=link&utm_content=25-policy-pages#wplegalpages-policy-templates" target="_blank">25+ policy pages</a> for your WordPress website.'
            }
        }),
        createElement('p', {
            domProps: {
                textContent: 'These policy pages are vetted by experts and are constantly updated to keep up with the latest regulations such as GDPR, CCPA, CalOPPA and many others.'
            }
        }),
        createElement('form', {
            staticClass: 'wplegal-input-checkbox',
            on: {
                submit: function(e) {
                    return e.preventDefault(),
                        self.handleSubmit(e);
                }
            }
        }, [createElement('label',{
            class: this.labelClass()
        },[createElement('span', {
            class: this.titleClass()
        }),
        createElement('input',{
            attrs:{
                type:'checkbox',
                name:'lp_accept_terms'
            },
            domProps: {
                value: 1,
                checked: this.isChecked()
            },
            on: {
                change: function(event) {
                    event.preventDefault();
                    event.target.checked = !event.target.checked;
                    self.updateSettings();
                }
            }
        }),createElement('span',{
            domProps: {
                innerHTML: 'By using WP Legal Pages, you accept the <a href=\"https://wplegalpages.com/product-terms-of-use/?utm_source=wplegalpages&utm_medium=getting-started&utm_campaign=link&utm_content=terms-of-use\" target=\"_blank\">terms of use</a>.'
            }
        })]),
        this.$parent.disabled ? [] : createElement('button',{
            staticClass: 'wplegal-button',
            attrs: {
                type: 'submit',
                name: 'accept_terms'
            },
            domProps: {
                textContent: 'Accept'
            }
        })])])]);
    }
});

Vue.component('SettingsSection', {
    render(createElement) {
        return createElement('div', {
            staticClass: 'wplegal-settings-section'
        }, [createElement('div', {
            staticClass: 'wplegal-section-content'
        }, [createElement('p',{
            domProps: {
                textContent: 'WP Legal Pages generates personalized legal pages for your website. To do this it needs to know a few details about your website. Please take a couple of minutes to set up your business details before you can generate a policy page for this website.'
            }
        }), createElement('a', {
            staticClass: 'wplegal-button',
            domProps: {
                textContent: 'Configure Details',
                href:obj.settings_url
            }
        })])]);
    }
});

Vue.component('PagesSection', {
    render(createElement) {
        return createElement('div', {
            staticClass: 'wplegal-pages-section'
        }, [createElement('div', {
            staticClass: 'wplegal-section-content'
        }, [createElement('p',{
            domProps: {
                textContent: 'Generate a personalized legal policy page your website.'
            }
        }), createElement('a', {
            staticClass: 'wplegal-button',
            domProps: {
                textContent: 'Create Page',
                href:obj.pages_url
            }
        })])]);
    }
});

Vue.component('FeaturesSection', {
    render(createElement) {
        return createElement('div', {
            staticClass: 'wplegal-features-section'
        }, [createElement('div', {
            staticClass: 'wplegal-section-title'
        }, [createElement('p', {
            staticClass: 'wplegal-title-heading',
            domProps: {
                textContent: 'WP Legal Pages Features'
            }
        }), createElement('p', {
            staticClass: 'wplegal-title-subheading',
            domProps: {
                textContent: 'Why choose WP Legal Pages?'
            }
        })]), createElement('div',{
            staticClass: 'wplegal-section-content'
        }, [createElement('div', {
            staticClass: 'wplegal-feature'
        }, [createElement('div', {
            staticClass: 'wplegal-feature-icon'
        }, [createElement('img',{
            domProps:{
                src: obj.image_url + 'powerful-yet-simple.png'
            }
        })]), createElement('div', {
            staticClass: 'wplegal-feature-content'
        }, [createElement('p', {
            staticClass: 'wplegal-feature-title',
            domProps: {
                textContent: 'Powerful yet simple'
            }
        }), createElement('p',{
            staticClass: 'wplegal-feature-text',
            domProps: {
                textContent: 'Add 25+ legal policy pages to your WordPress website in less than 5 minutes.'
            }
        })])]), createElement('div', {
            staticClass: 'wplegal-feature'
        }, [createElement('div', {
            staticClass: 'wplegal-feature-icon'
        }, [createElement('img',{
            domProps:{
                src: obj.image_url + 'pre-built-templates.png'
            }
        })]), createElement('div', {
            staticClass: 'wplegal-feature-content'
        }, [createElement('p', {
            staticClass: 'wplegal-feature-title',
            domProps: {
                textContent: 'Pre-built templates'
            }
        }), createElement('p',{
            staticClass: 'wplegal-feature-text',
            domProps: {
                textContent: 'Choose from 25+ lawyer approved, legal policy pages from GDPR policies to affiliate disclosures.'
            }
        })])]), createElement('div', {
            staticClass: 'wplegal-feature'
        }, [createElement('div', {
            staticClass: 'wplegal-feature-icon'
        }, [createElement('img',{
            domProps:{
                src: obj.image_url + 'editable-templates.png'
            }
        })]), createElement('div', {
            staticClass: 'wplegal-feature-content'
        }, [createElement('p', {
            staticClass: 'wplegal-feature-title',
            domProps: {
                textContent: 'Editable templates'
            }
        }), createElement('p',{
            staticClass: 'wplegal-feature-text',
            domProps: {
                textContent: 'Edit or create your own legal policy templates using the WYSIWYG WordPress editor.'
            }
        })])]), createElement('div', {
            staticClass: 'wplegal-feature'
        }, [createElement('div', {
            staticClass: 'wplegal-feature-icon'
        }, [createElement('img',{
            domProps:{
                src: obj.image_url + 'gdpr-compliance.png'
            }
        })]), createElement('div', {
            staticClass: 'wplegal-feature-content'
        }, [createElement('p', {
            staticClass: 'wplegal-feature-title',
            domProps: {
                textContent: 'GDPR compliance'
            }
        }), createElement('p',{
            staticClass: 'wplegal-feature-text',
            domProps: {
                textContent: 'Easy to use shortcodes to display business information in legal policy pages.'
            }
        })])]), createElement('div', {
            staticClass: 'wplegal-feature'
        }, [createElement('div', {
            staticClass: 'wplegal-feature-icon'
        }, [createElement('img',{
            domProps:{
                src: obj.image_url + 'forced-consent.png'
            }
        })]), createElement('div', {
            staticClass: 'wplegal-feature-content'
        }, [createElement('p', {
            staticClass: 'wplegal-feature-title',
            domProps: {
                textContent: 'Forced consent'
            }
        }), createElement('p',{
            staticClass: 'wplegal-feature-text',
            domProps: {
                textContent: 'Force website visitors to agree to your Terms, Privacy Policy, etc using post / page lock down features.'
            }
        })])]), createElement('div', {
            staticClass: 'wplegal-feature'
        }, [createElement('div', {
            staticClass: 'wplegal-feature-icon'
        }, [createElement('img',{
            domProps:{
                src: obj.image_url + 'easy-shortcodes.png'
            }
        })]), createElement('div', {
            staticClass: 'wplegal-feature-content'
        }, [createElement('p', {
            staticClass: 'wplegal-feature-title',
            domProps: {
                textContent: 'Easy shortcodes'
            }
        }), createElement('p',{
            staticClass: 'wplegal-feature-text',
            domProps: {
                textContent: 'Easy to use shortcodes to display business information in legal policy pages.'
            }
        })])]), createElement('div', {
            staticClass: 'wplegal-feature'
        }, [createElement('div', {
            staticClass: 'wplegal-feature-icon'
        }, [createElement('img',{
            domProps:{
                src: obj.image_url + 'easy-to-install.png'
            }
        })]), createElement('div', {
            staticClass: 'wplegal-feature-content'
        }, [createElement('p', {
            staticClass: 'wplegal-feature-title',
            domProps: {
                textContent: 'Easy to install'
            }
        }), createElement('p',{
            staticClass: 'wplegal-feature-text',
            domProps: {
                textContent: 'WP Legal Pages is super-easy to install. Download & install takes less than 2 minutes.'
            }
        })])]), createElement('div', {
            staticClass: 'wplegal-feature'
        }, [createElement('div', {
            staticClass: 'wplegal-feature-icon'
        }, [createElement('img',{
            domProps:{
                src: obj.image_url + 'helpful-docs-guides.png'
            }
        })]), createElement('div', {
            staticClass: 'wplegal-feature-content'
        }, [createElement('p', {
            staticClass: 'wplegal-feature-title',
            domProps: {
                textContent: 'Helpful docs & guides'
            }
        }), createElement('p',{
            staticClass: 'wplegal-feature-text',
            domProps: {
                textContent: 'Even if you get stuck using WP Legal Pages, you can use our easy to follow docs & guides.'
            }
        })])]), createElement('div', {
            staticClass: 'wplegal-feature'
        }, [createElement('div', {
            staticClass: 'wplegal-feature-icon'
        }, [createElement('img',{
            domProps:{
                src: obj.image_url + 'multilingual-support.png'
            }
        })]), createElement('div', {
            staticClass: 'wplegal-feature-content'
        }, [createElement('p', {
            staticClass: 'wplegal-feature-title',
            domProps: {
                textContent: 'Multilingual support'
            }
        }), createElement('p',{
            staticClass: 'wplegal-feature-text',
            domProps: {
                textContent: 'Supports multi-language translations for English, French, Spanish, German, Italian, Portuguese.'
            }
        })])])])]);
    }
});

Vue.component('WizardSection', {
    render(createElement) {
        return createElement('div', {
            staticClass: 'wplegal-wizard-section'
        }, [createElement('div', {
            staticClass: 'wplegal-section-content'
        }, [createElement('p',{
            domProps: {
                textContent: 'Use our newly created wizard to create legal pages for your website.'
            }
        }), createElement('p',{
            domProps: {
                textContent: '(with just a few clicks)'
            }
        }), createElement('a', {
            staticClass: 'wplegal-button',
            domProps: {
                textContent: 'Launch Wizard',
                href:obj.wizard_url
            }
        })])]);
    }
});

var app = new Vue({
    el: '#gettingstartedapp',
    data: {
        is_pro: obj.is_pro,
        disabled: !1
    },
    render(createElement) {
        return createElement('div',{
            staticClass: 'wplegal-container'
        },[createElement('header-section'),
        createElement('div',{
            staticClass: 'wplegal-container-main'
        },[createElement('welcome-section'), createElement('video-section'), createElement('terms-section'), this.disabled && !this.is_pro ? createElement('settings-section') : [], this.disabled && !this.is_pro ? createElement('pages-section') : []]),
        createElement('div',{
        staticClass: 'wplegal-container-features'
        },[this.is_pro ? this.disabled ? createElement('wizard-section') : [] : createElement('features-section')])]);
    }
});

var vm = new Vue({
    el: '#wplegal-mascot-app',
    data: function() {
        return {
            showMenu: !1,
            isPro:obj.is_pro
        }
    },
    computed: (
        {
            boxClass() {
                return {
                    'wplegal-mascot-quick-links wplegal-mascot-quick-links-open' : this.showMenu,
                    'wplegal-mascot-quick-links' : !this.showMenu,
                }
            },
            menuItems() {
                var mItems = [
                    {
                        icon: 'dashicons-lightbulb',
                        tooltip: 'Support',
                        link: obj.support_url,
                        key: 'support'
                    },
                    {
                        icon: 'dashicons-info',
                        tooltip: 'FAQ',
                        link: obj.faq_url,
                        key: 'faq'
                    },
                    {
                        icon: 'dashicons-sos',
                        tooltip: 'Documentation',
                        link: obj.documentation_url,
                        key: 'documentation'
                    }
                ];
                if(!this.isPro) {
                    mItems.push({
                        icon: 'dashicons-star-filled',
                        tooltip: 'Upgrade to Pro »',
                        link: obj.upgrade_url,
                        key: 'upgrade'
                    });
                }
                return mItems;
            }
        }
    ),
    methods:{
        buttonClick: function(){
            this.showMenu = !this.showMenu;
        },
        renderElements:function(createElement) {
            var html = [];
            if(this.showMenu) {
                this.menuItems.forEach((value, index) => {
                    html.push(createElement('a', {
                        key: value.key,
                        class: this.linkClass(value.key),
                        attrs: {
                            href: value.link,
                            'data-index': index,
                            target: '_blank'
                        }
                    }, [createElement('span', {
                        class: 'dashicons '+ value.icon
                    }), createElement('span', {
                        staticClass: 'wplegal-mascot-quick-link-title',
                        domProps: {
                            innerHTML: value.tooltip
                        }
                    })]));
                })
            }
            return html;
        },
        linkClass: function(key) {
            return 'wplegal-mascot-quick-links-menu-item wplegal-mascot-quick-links-item-' + key;
        },
        enter:function(t,e) {
            var n = 50 * t.dataset.index;
            setTimeout((function() {
                t.classList.add('wplegal-mascot-show'),
                    e()
            }), n)
        },
        leave:function(t,e) {
            t.classList.remove('wplegal-mascot-show'),
                setTimeout((function() {
                    e()
                }), 200)
        }
    },
    render(createElement){
        return createElement('div',{
            class: this.boxClass,
        }, [
            createElement('button', {
                class: 'wplegal-mascot-quick-links-label',
                on: {
                    click: this.buttonClick
                }
            },[
                createElement('span', {
                    class:'wplegal-mascot-bg-img wplegal-mascot-quick-links-mascot',
                }),
                createElement('span',{
                    class: 'wplegal-mascot-quick-link-title'
                }, 'See Quick Links')
            ]),
            createElement('transition-group', {
                staticClass: 'wplegal-mascot-quick-links-menu',
                attrs:{
                    tag: 'div',
                    name: 'wplegal-staggered-fade'
                },
                on: {
                    enter: this.enter,
                    leave: this.leave
                }
            }, this.renderElements(createElement))
        ]);
    },
});