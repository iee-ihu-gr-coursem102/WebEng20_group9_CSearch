<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
       <!--<nav class="navbar navbar-expand-lg navbar-light bg-light">-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">

            <a class="navbar-brand" href="#">Navbar</a>

            <button class="navbar-toggler" type="button" 
                    data-toggle="collapse" data-target="#navbarSupportedContent" 
                    aria-controls="navbarSupportedContent" aria-expanded="false" 
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>


            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="home.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item active">
                        <!--<a class="nav-link" href="2nd_page_just_php.php">Events <span class="sr-only">(current)</span></a>-->
                        <a class="nav-link" href="events.php">Events <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="#">About <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>


                </ul>

                <ul class="nav navbar-nav navbar-right">

                    <li>
                        <button class="btn btn-outline-success my-2 my-sm-0 " 
                                id="login_button" 
                                data-target="#my_modal"
                                >Login</button>
                    </li>
                    <li>
                        <button class="btn btn-outline-success my-2 my-sm-0"
                                id="sign_up_button" 
                                type="submit"
                                >SignUp</button>
                    </li>
                    <li>
                        <button class="btn btn-outline-success my-2 my-sm-0"
                                id="exit_button" 
                                type="submit"
                                >Exit</button>
                    </li>

                </ul>

            </div>
        </nav>

