<template>
    <div class="contact-wrapper">
        <div class="contact-wrapper-inner">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="contact-form-wrapper">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="contact-title">
                                        <h6>Submit Work and Services</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="post-comment-form-group">
                                        <form action="#" method="post">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Client Name</label>
                                                    <input type="text" name="name" placeholder="Client Name*"
                                                           class="form-control" v-model="workPostData.name" required=""
                                                           aria-required="true">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Phone</label>
                                                    <input type="text" class="form-control" maxlength="10"
                                                           @input="workPostData.phone = workPostData.phone.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1')"
                                                           v-model="workPostData.phone" placeholder="Contact No*">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Category</label>
                                                    <select class="form-control" id="editCategoryId" v-model="workPostData.category" v-on:change="getSubCategories(workPostData.category)">
                                                        <option value="">Select Category</option>
                                                        <option v-for="o in CategoryData" v-bind:value="o.id">{{o.category_name}}</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Subcategory</label>
                                                    <select class="form-control" id="subCategoryId" v-model="workPostData.subcategory">
                                                        <option value="">Select Subcategory</option>
                                                        <option v-for="o in SubCategoryData" v-bind:value="o.id">{{o.sub_category_name}}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Budget</label>
                                                    <div id="price-range"></div>
                                                    <div class="row mb-2">
                                                        <div id="price-min-value" class="col-md-6"></div>
                                                        <div id="price-max-value"
                                                             class="col-md-6 text-right"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Upload File</label>
                                                    <input type="file" ref="image"/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>Work Description</label>
                                                    <textarea name="Description" class="form-control" placeholder="Description*"
                                                           v-model="workPostData.description" required=""
                                                              aria-required="true"></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6"></div>
                                                <div class="col-md-6">
                                                    <input type="button" value="Submit" v-on:click="validateForm" :disabled="workPostData.disabled"
                                                           class="btn btn-block btn-brand btn-lg">
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<style>
    .btn-align {
        vertical-align: top !important;
    }

    .btn-send-otp {
        height: 40px !important;
    }
    .noUi-connect {
        background: #3b5898 !important;
    }

</style>

<script>
    import UserMixin from "../mixins/UserMixin";

    export default {
        name: 'Register',
        data() {
            return {
                workPostData: {
                    "name": "",
                    "phone": "",
                    "subcategory":"",
                    "category":"",
                    "minBudget":"",
                    "maxBudget":"",
                    "description":"",
                    "image":"",
                    "disabled": false
                },
            }
        },
        created() {
            let that = this;
            that.getState();
            that.getCategories();

            setTimeout(function () {


                var nonLinearSlider = document.getElementById('price-range');

                noUiSlider.create(nonLinearSlider, {
                    connect: true,
                    behaviour: 'tap',
                    start: [0, 100000],
                    range: {
                        // Starting at 500, step the value by 500,
                        // until 4000 is reached. From there, step by 1000.
                        'min': [10],
                        '10%': [500, 500],
                        '50%': [4000, 1000],
                        'max': [100000]
                    }
                });

                $("#price-range-value").empty();
                nonLinearSlider.noUiSlider.on('update', function (values, handle) {
                    $("#price-min-value").html(values[0]);
                    $("#price-max-value").html(values[1]);
                });
            }, 2500)
        },
        mixins: [UserMixin],
        methods: {
            validateForm: function () {
                let that = this;
                if (!that.workPostData.name) {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Name is required",
                        showConfirmButton: true
                    });
                } else if (!that.workPostData.phone) {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Phone is required",
                        showConfirmButton: true
                    });
                } else if (!that.workPostData.category) {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Category is required",
                        showConfirmButton: true
                    });
                } else if (!that.workPostData.subcategory) {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Subcategory is required",
                        showConfirmButton: true
                    });
                } else if (!that.workPostData.description) {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Description is required",
                        showConfirmButton: true
                    });
                }
                // else if (!that.$refs.image.files.length) {
                //     this.$swal({
                //         type: "error",
                //         title: "Error",
                //         text: "Image is required",
                //         showConfirmButton: true
                //     });
                // }
                else {
                    that.workPost();
                }

            },
            workPost: function () {
                let that = this;
                that.workPostData.disabled = true;
                that.workPostData.minBudget = $("#price-min-value").html();
                that.workPostData.maxBudget = $("#price-max-value").html();
                let formData = new FormData();
                var image = that.$refs.image.files[0];
                formData.append('image', image);
                formData.append('name', that.workPostData.name);
                formData.append('phone', that.workPostData.phone);
                formData.append('category', that.workPostData.category);
                formData.append('subcategory', that.workPostData.subcategory);
                formData.append('minBudget', that.workPostData.minBudget);
                formData.append('maxBudget', that.workPostData.maxBudget);
                formData.append('description', that.workPostData.description);
                axios.post(APP_URL + '/work-post-by-user', formData).then(response => {
                    that.workPostData.disabled = false;
                    that.$swal({
                        type: "success",
                        title: "Success",
                        text: response.data.message,
                        showConfirmButton: true
                    }).then(function () {
                        window.location = APP_URL + '/my-works';
                    });
                }).catch((error) => {
                    that.workPostData.disabled = false;
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: error.response.data.message,
                        showConfirmButton: true
                    });

                });
            }

        }
    }
</script>
