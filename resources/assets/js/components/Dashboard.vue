<template>
    <div class="container">
        <section class="content-header">

        </section>
        <section class="content">
      <!-- Info boxes -->
      <div class="row">
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>{{allenrollments[0]}}</h3>

              <p>Users</p>
            </div>
            <div class="icon">
              <i class="fa fa-users nav-icon"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>{{allenrollments[1]}}</h3>

              <p>Courses</p>
            </div>
            <div class="icon">
              <i class="fab fa-leanpub nav-icon"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>{{allenrollments[2]}}</h3>

              <p>Enrollments</p>
            </div>
            <div class="icon">
              <i class="fas fa-sign-in-alt nav-icon"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
      <!-- /.row -->

      <!-- Main row -->
      <div class="row">
        <div class="col-md-8">
            <div class="info-box">
              <!-- USERS LIST -->
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Latest Members</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                  <ul class="users-list clearfix">
                    <li v-for="user in users" :key="user.id">
                      <img v-bind:src="'/img/' + user.photo" >
                      <a class="users-list-name" href="#">{{user.name}}</a>
                      <span class="users-list-date">{{user.created_at | viewDate}}</span>
                    </li>
                  </ul>
                  <!-- /.users-list -->
                </div>
              </div>
              <!--/.box -->
            </div>
        </div>
        <div class="col-md-4">         
        <div class="info-box">
          <!-- TABLE: LATEST ORDERS -->
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Latest Enrollments</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th>Course</th>
                    <th>Student</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="enrollment in enrollments" :key="enrollment.id">
                    <td>{{enrollment.course.title}}</td>
                    <td>{{enrollment.user.name }}</td>
                  </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
          </div>
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- /.row -->
    </section>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                editMode:false,
                allenrollments: '',
                users: {},
                enrollments:{}
            }
        },
        methods: {    
            LoadUsers(){
                axios.get("api/getlatestusers").then(({ data }) => (this.users=data.data))
            },  
            LoadEnrollments(){
                axios.get("api/getlatestenrollments").then(({ data }) => (this.enrollments=data.data))
            },       
            CountAllData(){
                axios.get("api/dashboard").then(({ data }) => (this.allenrollments=data))
            }
        },
        created() {
            this.CountAllData();
            this.LoadUsers();
            this.LoadEnrollments();
        }
    }
</script>

