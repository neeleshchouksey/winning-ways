<template>
    <div class="blog-item-wrapper">
        <div class="single-blog-item">
            <div class="row">

                <div class="col-md-4" v-if="blogData.length" v-for="b in blogData">
                    <div class="blog-thumb">
                        <a :href="APP_URL+'/blog-details/'+b.id">
                            <img :src="APP_URL+'/public/storage/blog-images/'+b.image"
                                 alt="Blog" class="img-responsive img-rounded"/>
                        </a>
                    </div>
                    <div class="blog-content">
                        <h4><a :href="APP_URL+'/blog-details/'+b.id">{{b.name}}</a>
                        </h4>
                        <div class="blog-tags">
                            <ul>
                                <li class="blog-date"><a href="#"><i class="fa fa-clock-o"
                                                                     aria-hidden="true"></i>{{b.created_at | formatDate}}
                                </a></li>
                            </ul>
                        </div>
                        <p>{{b.description | striphtml(150)}}</p>
                        <a class="btn btn-brand readmore"
                           :href="APP_URL+'/blog-details/'+b.id">Read More</a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</template>


<script>
    import UserMixin from "../mixins/UserMixin";

    export default {
        name: 'Package',
        data() {
            return {
               blogDataPage: {},
               blogData: [],
            }
        },
        created() {
            let that = this;
            that.getBlogs();
        },
        mixins: [UserMixin],
        methods: {

            getBlogs: function (page = 1) {
                let that = this;
                axios.get(APP_URL + '/get-blogs?page=' + page).then(response => {
                        that.blogDataPage = response.data.res;
                        that.blogData = response.data.res.data;

                    }
                ).catch((error) => {

                });
            },
        }
    }
</script>
