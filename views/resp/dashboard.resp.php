<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/assets/css/bootstrap.min.css">
 <!--   <link rel="stylesheet" href="/css/bootstrap.min.css"> --> 
<!-- icons-->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <title>intership </title>
</head>
<body>
 <div class="container">
    <vmenu>
        <div class="top">
            <div class="logo">
                <img src="../../public/assets/img/logo_FSTM.png" alt="Logo de la FSTM">
                <h2>intern<span class="primary">ship</span></h2>
            </div>
            <div class="close" id="close-btn">
                <span class="material-icons-sharp">close</span>
            </div>
        </div>
        <div class="sidebar">
            <a href="#" class="active">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="#" >
                <span class="material-icons-sharp"> person</span>
                <h3>students</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp"> work_history</span>
                <h3>internship</h3>
            </a>
            
            <a href="#">
                <span class="material-icons-sharp">person_add</span>
                <h3>validation</h3>
                <span class="validation-count">6</span>
            </a>
            <a href="#">
                <span class="material-icons-sharp"> business</span>
                <h3>Companies</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp"> post_add</span>
                <h3>internship offer</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp"> edit</span>
                <h3>Edit profile</h3>
            </a>

            
            <a href="/login?logout">
                <span class="material-icons-sharp">logout</span>
                <h3>logOut</h3>
            </a>
            
           
        </div>
    </vmenu>


<!-- end of vmenu-->
<main>
    <h1>Dashboard</h1>
    <div class="date">
        <input type="date">
    </div>
    <div class="insights">
        <!--internship-->
            <div class="internship">
                <span class="material-icons-sharp">lightbulb_circle</span>
                <div class="middle">
                    <div class="left">
                        <h3> internship</h3>
                        <h1>45</h1>
                    </div>
                    <div class="progress">
                        <svg>
                            <circle cx="38" cy="38" r="36"></circle>
                         </svg>   
                        <div class="number">
                            <p>81%</p>
                        </div>
                        <small class="text-muted">Ended</small>
                    </div>
                </div>
              
            </div>
            <!--End of internship-->

             <!--validation-->
             <div class="validation">
                <span class="material-icons-sharp">done</span>
                <div class="middle">
                    <div class="left">
                        <h3> validation tasks</h3>
                        <h1>5</h1>
                    </div>
                    <div class="progress">
                        <svg>
                            <circle cx="38" cy="38" r="36"></circle>
                         </svg>   
                            <div class="number">
                                <p>5</p>
                                
                            </div>
                            <small class="text-muted">last 24 Hours</small>
                    </div>
                </div>
                
            </div>
            <!--End of validation-->
              <!--offer-->
              <div class="offer">
                <span class="material-icons-sharp">loyalty</span>
                <div class="middle">
                    <div class="left">
                        <h3>internship offer </h3>
                        <h1>5</h1>
                    </div>
                    <div class="progress">
                        <svg>
                            <circle cx="38" cy="38" r="36"></circle>
                         </svg>   
                            <div class="number">
                                <p>81%</p>
                            </div>
                            <small class="text-muted">deadline</small>
                            
                    </div>
                </div>
               
            </div>
            <!--End of offer-->
    </div>
    <div class="recent-event">
        <h2> Recent internships offer</h2>
        <table>
            <thead>
                <tr>
                    <th>Num</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>event</td>
                    <td class="warning">status </td>
                    <td class="primary"> details</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>event</td>
                    <td class="warning">status </td>
                    <td class="primary"> details</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>event</td>
                    <td class="warning">status </td>
                    <td class="primary"> details</td>
                </tr>
            </tbody>
        </table>
        <a href="#"> Show All</a>
    </div>
</main>
  <!--------------------End of main------------->
  <div class="right">
    <div class="top">
        <button id="menu-btn">
            <span class="material-icons-sharp">menu</span>
        </button>
        <div class="theme-toggler">
            <span class="material-icons-sharp active">light_mode</span>
            <span class="material-icons-sharp ">dark_mode</span>
        </div>
        <div class="profile">
            <div class="info">
                <p> Hey,UserName</p>
                <small class="text-muted">Admin</small>
            </div>
            <div class="profile-photo">
                <img src="../../public/assets/img/profile.jpg">
            </div>
        </div>
    </div>
      <!--------------------End of TOP------------->
      <div class="recent-updates">
        <h2> recent Companies</h2>
        <div class="updates">
            <div class="update">
                <div class="profile-photo">
                    
                    <img src="../../public/assets/img/comp1.jpg">
                </div>
                <div class="message">
                    <p> company1 </p>
                    <small class="text-muted">added 21-05-2022 </small>
                </div>
            </div>
            <!-- End comp1-->
            <div class="update">
                <div class="profile-photo">
                    
                    <img src="../../public/assets/img/comp2.jpg">
                </div>
                <div class="message">
                    <p> company2</p>
                    <small class="text-muted">added 21-05-2022 </small>
                </div>
            </div>
            <!-- End comp2-->
            <div class="update">
                <div class="profile-photo">
                    
                    <img src="../../public/assets/img/comp1.jpg">
                </div>
                <div class="message">
                    <p> company3</p>
                    <small class="text-muted">added 21-05-2022 </small>
                </div>
            </div>
            <!-- End comp3-->
        </div>
      </div>
       <!-- End of recent companies-->
      <div class="recent-add">
          <h2>Recent added Students</h2>
           <!--Element-->
          <div class="element">
              <div class="icon">
                <span class="material-icons-sharp">gamepad</span>
              </div>
              <div class="right">
                  <div class="info">
                      <h3> Student Name</h3>
                      <small class="text-muted">ilisi2</small>
                  </div>
                
              </div>
          </div>
           <!-- End of element-->
               <!--Element-->
          <div class="element">
            <div class="icon">
              <span class="material-icons-sharp">gamepad</span>
            </div>
            <div class="right">
                <div class="info">
                    <h3> Student Name</h3>
                    <small class="text-muted">ilisi2</small>
                </div>
              
            </div>
        </div>
         <!-- End of element-->
             <!--Element-->
             <div class="element">
                <div class="icon">
                  <span class="material-icons-sharp">gamepad</span>
                </div>
                <div class="right">
                    <div class="info">
                        <h3> Student Name</h3>
                        <small class="text-muted">ilisi3</small>
                    </div>
                  
                </div>
            </div>
             <!-- End of element-->
      </div>
  </div>

 </div>
 <script src="../../public/assets/js/code.js"></script>
 <!-- <script src="/js/bootstrap.bundle.min.js"></script>-->
</body>
</html>