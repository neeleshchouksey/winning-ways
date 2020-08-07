<template>
    <div class="dashboard-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12 no-padding-left-right">
                    <div class="dashboard-title text-center">
                        <h6>My Works</h6>
                    </div>
                </div>

            </div>
            <div class="row">

                <div class="col-md-12 no-padding-left-right">
                    <div class="dashboard-wrap">
                        <div class="dashboard-table-attr-type">
                            <ul>
                                <li class="tab"><a href="javascript:void(0)" v-on:click="getWorks(0)">Pending</a></li>
                                <li class="tab"><a href="javascript:void(0)" v-on:click="getWorks(1)">Progress</a></li>
                                <li class="tab"><a href="javascript:void(0)" v-on:click="getWorks(2)">Deal</a></li>
                                <li class="tab"><a href="javascript:void(0)" v-on:click="getWorks(3)">Completed</a></li>
                                <li class="tab"><a href="javascript:void(0)" v-on:click="getWorks(4)">Delivered</a></li>
                                <li class="tab active"><a href="javascript:void(0)" v-on:click="getWorks(5)">All</a></li>
                            </ul>
                        </div>
                        <div class="table-responsive">
                        <table class="table"  v-if="workData.length">
                            <thead>
                            <tr>
                                <th>S.L</th>
                                <th>Work Name</th>
                                <th>Category</th>
                                <th>Subcategory</th>
                                <th>Description</th>
                                <th>Min Budget</th>
                                <th>Max Budget</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(wd,index) in workData">
                                <td>{{index + 1}}</td>
                                <td>{{wd.name}}</td>
                                <td>{{wd.category_name}}</td>
                                <td>{{wd.sub_category_name}}</td>
                                <td class="pre-format">{{wd.description}}</td>
                                <td>{{wd.min_budget}}</td>
                                <td>{{wd.max_budget}}</td>
                                <td>{{wd.status | getStatus(wd.status)}}</td>
                            </tr>
                            </tbody>
                        </table>

                        <pagination :data="workDataPage"
                                    @pagination-change-page="getWorks"></pagination>

                        <p class="no-data-foun" v-if="!workData.length">No Data Found</p>
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
        name: 'MyWorks',
        data() {
            return {
                workData: [],
                workDataPage: {},
            }
        },
        created() {
            let that = this;
            that.getWorks(5);

            $(document).ready(function() {
                $(".tab").click(function () {
                    $(".tab").removeClass("active");
                    // $(".tab").addClass("active"); // instead of this do the below
                    $(this).addClass("active");
                });
            });

        },
        mixins: [UserMixin],
        methods: {
            getWorks: function (status) {
                let that = this;
                axios.get(APP_URL + '/get-my-works/'+status).then(response => {
                    that.workData = response.data.res.data;
                    that.workDataPage = response.data.res;
                    (".active").removeClass("active");
                    ("#t"+status).addClass("active");

                    }
                ).catch((error) => {

                });
            },

        }
    }
</script>
