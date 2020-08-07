<template>
    <div class="pricing-table-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="section-heading"><span>Pr</span>ofile</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-sm-12 col-lg-12 col-xl-12" v-for="pd in PackageData"
                     v-bind:class="{'single-pricing-wrapper-active':(pd.id == selectedPlan)}">
                    <div class="image-chang">
                        <img :src="APP_URL+'/public/storage/package-images/'+pd.image" alt="">
                        <div class="butan-logi">
<!--                            <a v-if="pd.id == selectedPlan && selected_plan_status==1">View Details</a>-->
<!--                            <a v-if="pd.id == selectedPlan && selected_plan_status==0">Requested</a>-->
<!--                            <a v-if="pd.id != selectedPlan" href="javascript:void(0)" data-toggle="modal" data-target="#package-modal" v-on:click="getSP(pd.id)">View Details</a>-->
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#package-modal" v-on:click="getSP(pd.id)">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal -->
        <div id="package-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Work and Service Details</h4>
                    </div>
                    <div class="modal-body">
                        <div class="coupon-modal-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" name="name" placeholder="Name*"
                                           class="form-control" v-model="searchData.service" required=""
                                           aria-required="true">
                                </div>
                                <div class="col-md-3">
                                    <button type="button"
                                            class="btn btn-block btn-brand btn-lg" v-on:click="getSinglePackage">Search
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <button type="button"
                                            class="btn btn-block btn-brand btn-lg" v-on:click="resetFilter">Reset
                                    </button>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class=" col-md-12">
                                    <div class="dashboard-wrap table table-responsive">
                                        <table class="table" v-if="workPackageData.length">
                                            <thead>
                                            <tr>
                                                <th>S. No.</th>
                                                <th>Work and Service Name</th>
<!--                                                <th>Package Name</th>-->
                                                <th>Commission</th>
                                                <!--                                    <th>Package 3</th>-->
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="(wd,index) in workPackageData">
                                                <td>{{index + 1}}</td>
                                                <td><a :href="APP_URL+'/subcategory/'+wd.subcategory_id">{{wd.sub_category_name}}</a></td>
<!--                                                <td>{{wd.name}}</td>-->
                                                <td>{{wd.commission}}%</td>
                                                <!--                                    <td>{{wd.package3}}</td>-->
                                            </tr>
                                            </tbody>
                                        </table>
                                        <pagination :data="workPackageDataPage"
                                                    @pagination-change-page="getSinglePackage"></pagination>
                                        <p v-if="!workPackageData.length">No Data Found</p>
                                    </div>
                                    <button class="btn btn-success" style="float: right;" v-on:click="purchasePlan">
                                        Request for Plan</button>
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

    .image-chang img {
        width: 100%;
    }

    .mt-5 {
        margin-top: 3rem !important;
    }
</style>

<script>
    import UserMixin from "../mixins/UserMixin";

    export default {
        name: 'Package',
        data() {
            return {
                PackageData: [],
                selectedPlan: "",
                selected_plan_validity: "",
                selected_plan_status: "",
                workPackageData: [],
                workPackageDataPage: {},
                packageId:"",
                searchData:{
                    service:""
                }
            }
        },
        created() {
            let that = this;
            that.getPackages();
        },
        mixins: [UserMixin],
        methods: {
            resetFilter: function(){
              let that = this;
              that.searchData.service = "";
              that.getSinglePackage();
            },
            getPackages: function (page = 1) {
                let that = this;
                axios.get(APP_URL + '/get-packages?page=' + page).then(response => {
                        that.PackageData = response.data.res;
                        that.selectedPlan = response.data.selected_plan;
                        that.selected_plan_validity = response.data.selected_plan_validity;
                        that.selected_plan_status = response.data.selected_plan_status;
                    }
                ).catch((error) => {

                });
            },
            getSP: function (id) {
                let that = this;
                that.packageId = id;
                that.getSinglePackage();
            },
            getSinglePackage: function (page = 1) {
                let that = this;
                axios.post(APP_URL + '/get-single-package/'+that.packageId+'?page=' + page,that.searchData).then(response => {
                        that.workPackageDataPage = response.data.work_package;
                        that.workPackageData = response.data.work_package.data;
                    }
                ).catch((error) => {

                });
            },
            purchasePlan: function () {
                let that = this;
                if (!AUTH_USER) {
                    var cur_url = document.URL;
                    console.log(cur_url);
                    cur_url = encodeURIComponent(cur_url);
                    const el = document.createElement('div')
                    el.innerHTML = "Please <a style='color: #9f8447' href='" + APP_URL + "/signin?url=" + cur_url + "'>Login</a> or <a style='color: #9f8447' href='" + APP_URL + "/signup?url=" + cur_url + "'>Register</a> to Continue"
                    this.$swal({
                        title: "Warning!",
                        html: el,
                        icon: "warning",
                    }).then(function () {
                        window.location = APP_URL + "/signin?url=" + cur_url;
                    });
                } else {
                    axios.get(APP_URL + '/purchase-package/' + that.packageId).then(response => {
                            this.$swal({
                                type: "success",
                                title: "Success",
                                text: "You have successfully requested for this plan,one of our executive will contact you soon",
                                showConfirmButton: true
                            }).then(function () {
                                window.location.reload();
                            });
                        }
                    ).catch((error) => {

                    });
                }
            }
        }
    }
</script>
