<template>
    <div class="dashboard-wrapper">
        <div class="container">

            <div class="row mt-3 mb-3">
                <div class="col-md-4">
                </div>
                <div class="col-md-6">
                </div>
                <div class="col-md-2 mb-4">
                    <download-excel class="btn btn-block btn-brand btn-lg text-right"
                                    :data="exportedData">
                        Export Excel
                    </download-excel>
                </div>
            </div>
            <div class="row mt-3 mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <select name="coupon_cat" id="billing_state" class="form-control"
                                v-on:change="getCity">
                            <option value="">Select State *</option>
                            <option v-for="sd in stateData" :value="sd.id">{{sd.name}}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="coupon_cat" id="billing_city" class="form-control">
                            <option value="">Select City *</option>
                            <option v-for="cd in cityData" :value="cd.id">{{cd.name}}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="name" placeholder="Name*"
                               class="form-control" v-model="searchData.name" required=""
                               aria-required="true">
                    </div>
                    <div class="col-md-2">
                        <button type="button"
                                class="btn btn-block btn-brand btn-lg" v-on:click="getAchievers">Search
                        </button>
                    </div>
                    <div class="col-md-1">
                        <button type="button"
                                class="btn btn-block btn-brand btn-lg" v-on:click="resetFilter">Reset
                        </button>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-12 no-padding-left-right">
                    <div class="dashboard-wrap">
                        <table class="table table-responsive" v-if="paymentData.length">
                            <thead>
                            <tr>
                            <tr>
                                <th>S. No.</th>
                                <th>User Name</th>
                                <th>State</th>
                                <th>City</th>
                                <th>Pin Code</th>
                                <th>Total Earned Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(wd,index) in paymentData">
                                <td>{{index + 1}}</td>
                                <td>{{wd.name}}</td>
                                <td>{{wd.state}}</td>
                                <td>{{wd.city}}</td>
                                <td>{{wd.pin_code}}</td>
                                <td>{{wd.total_amt}}</td>
                            </tr>
                            </tbody>
                        </table>
                        <pagination :data="paymentDataPage"
                                    @pagination-change-page="getAchievers"></pagination>
                        <p v-if="!paymentData.length">No Data Found</p>
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
                paymentData: [],
                paymentDataPage: {},
                searchData: {
                    name: "",
                    state: "",
                    city: ""
                }
            }
        },
        created() {
            let that = this;
            that.getState();
            that.getAchievers();
        },
        mixins: [UserMixin],
        methods: {
            resetFilter: function () {
                let that = this;
                that.searchData.name = "";
                that.searchData.state = "";
                that.searchData.city = "";
                $("#billing_state").val("");
                $("#billing_city").val("");
                that.getAchievers();
            },
            getAchievers: function (page = 1) {
                let that = this;
                that.searchData.state = $("#billing_state").val();
                that.searchData.city = $("#billing_city").val();
                axios.post(APP_URL + '/get-achievers?page=' + page, that.searchData).then(response => {
                        that.paymentData = response.data.res.data;
                        that.exportedData = response.data.res.data;
                        that.paymentDataPage = response.data.res;
                    }
                ).catch((error) => {

                });
            },
        }
    }
</script>
