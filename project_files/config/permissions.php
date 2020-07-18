<?php


//['c','u','d','r','t','b','s']
return
    [

        'Models'=>[
            'admins'                    =>['c','r','d','u'],
            'universities'             =>['c','r','d','u'],
            'departments'           =>['c','r','d','u'],
            'instructors'              =>['c','r','d','u'],
            'article'                     =>['c','r','d','u'],
            'courses'                   =>['c','r','d','u'],
            'photos'                     =>['c','r','d','u'],
            'lesson_reviews'                       =>['r','d'],
            'course_reviews'                       =>['r','d'],
            'course_orders'                       =>['r','d','u'],
            'parts'                       =>['c','u','d'],
            'exams'                     =>['c','r','d','u'],
            'lessons'                    =>['c','r','d','u'],
            'files'                        =>['c','r','d','u'],
            'category'                  =>['c','r','d','u'],
            'post'                         =>['c','r','d','u'],
            'tag'                           =>['c','r','d','u'],
            'project'                    =>['c','r','d','u'],
            'feedback'                 =>['c','r','d','u'],
            //project
            //feedback
//            'settings'                   =>['u'],
            'users'                  =>['u','d','r']
//            'products'             =>['c','u','d','t','b','s'],
//            'clients'                =>['c','u','d','r','t','b','s'],
//            'cobons'               =>['u','d','r','t','b','s'],
//            'exams'                =>['u','d','r','t','b','s'],
//            'ocasions'             =>['u','d','r','t','b','s'],

            /* .. Register Here Your Models .. */
//            'lectures'             =>['u','d','r','t','b','s'],
        ],
        'Maps'=>[
            'r'                 =>'read',
            'c'                 =>'create',
            'u'                 =>'update',
            'd'                 =>'delete',

//            't'                 =>'trash',
//            'b'                 =>'restore',
//            's'                 =>'status',

            /* .. Register Here Your Operations .. */
        ],

    ];




