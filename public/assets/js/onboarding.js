// function getHistory(module_code) {
//     var condition = 0;
//     $.ajax({
//         type: "GET",
//         url: "onboarding/get-history/",
//         data: {
//             module_code: module_code
//         },
//         success: function (res) {
//             // return res;
//             condition = res;
//             alert("get history " + condition);
//             // return condition;

//         },
//         error: function () {
//         }
//     });

//     return condition;
// }
function addHistory(module_code) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "POST",
        url: "onboarding/add-history",
        data: {
            module_code: module_code
        },
        success: function (res) {

        },
        error: function () { }
    });
}
function intro(again = 0) {
    $.ajax({
        type: "GET",
        url: "onboarding/get-history",
        data: {
            module_code: 'PBENGINE/HOMEPAGE/INTRO'
        },
        success: function (res) {
            // return res;
            condition = res;

            if (again) {
                condition = 0;
            }

            if (!condition) {
                let intro = introJs();
                intro.setOptions({
                    exitOnOverlayClick: false,
                    steps: [{
                        title: 'Welcome',
                        intro: 'Selamat datang di PB Engine! 👋, mari kita mulai tour sistem PB Engine yang baru',
                        showButtons: false,
                    },
                    {
                        element: document.querySelector('.user-display'),
                        intro: 'Profil anda bisa dilihat disini',
                    },
                    {
                        element: document.querySelector('#dropdown-notification'),
                        intro: 'Anda dapat melihat notifikasi yang masuk di sini',
                    },
                    {
                        element: document.querySelector('[menu="Homepage"]'),
                        title: 'Menu',
                        intro: 'Homepage merupakan halaman utama dari sistem PB Engine',
                        position: 'right',
                    },
                    {
                        element: document.querySelector('[main-menu="Master"]'),
                        title: 'Main Menu',
                        intro: 'Master merupakan menu utama yang berisi menu untuk mengelola data master',
                        position: 'right',
                    },
                    {
                        element: document.querySelector('[main-menu="Memo"]'),
                        title: 'Main Menu',
                        intro: 'Memo merupakan menu utama yang berisi menu untuk mengelola memo, baik memo dari PPC, Picking List, ataupun Pulling Area',
                        position: 'right',
                    },
                    {
                        element: document.querySelector('[main-menu="Semifinish"]'),
                        title: 'Main Menu',
                        intro: 'Semifinish merupakan menu utama yang berisi menu untuk mengelola data semifinish, baik me-mapping semifinish maupun melihat progres memo',
                        position: 'right',
                    },
                    {
                        element: document.querySelector('[menu="Panduan"]'),
                        title: 'Menu',
                        intro: 'Panduan berisi daftar tutorial atau pengenalan fitur pada aplikasi',
                        position: 'right',
                    },

                    ]
                }).start();

                // document.querySelector('[main-menu="Master"]').addEventListener('click', function () {
                //     // alert('halo');
                //     intro.nextStep();
                // });

                intro.oncomplete(function () {
                    addHistory('PBENGINE/HOMEPAGE/INTRO');
                });
            }

        },
        error: function () {
        }
    });

}

// ------------------------------------------------------------------------- MASTER

// COMPONENT
// RAW MATERIAL
// BILL OF MATERIAL
// PRODUCT
// MATERIAL

// ------------------------------------------------------------------------- SEMIFINISH

// MAPPING STOCK
function TutorialMappingStock(history) {
    if (!history.includes('PBENGINE/SEMIFINISH/MAPPING')) {
        let intro = introJs();
        intro.setOptions({
            exitOnOverlayClick: false,
            steps: [{
                title: 'Welcome',
                intro: 'Selamat datang di PB Engine! 👋, mari kita mulai tour sistem PB Engine yang baru',
                showButtons: false,
            },
            {
                element: document.querySelector('.user-display'),
                intro: 'Profil anda bisa dilihat disini',
            },
            {
                element: document.querySelector('#dropdown-notification'),
                intro: 'Anda dapat melihat notifikasi yang masuk di sini',
            },
            {
                element: document.querySelector('[menu="Homepage"]'),
                title: 'Menu',
                intro: 'Homepage merupakan halaman utama dari sistem PB Engine',
                position: 'right',
            },
            {
                element: document.querySelector('[main-menu="Master"]'),
                title: 'Main Menu',
                intro: 'Master merupakan menu utama yang berisi menu untuk mengelola data master',
                position: 'right',
            },
            {
                element: document.querySelector('[main-menu="Memo"]'),
                title: 'Main Menu',
                intro: 'Memo merupakan menu utama yang berisi menu untuk mengelola memo, baik memo dari PPC, Picking List, ataupun Pulling Area',
                position: 'right',
            },
            {
                element: document.querySelector('[main-menu="Semifinish"]'),
                title: 'Main Menu',
                intro: 'Semifinish merupakan menu utama yang berisi menu untuk mengelola data semifinish, baik me-mapping semifinish maupun melihat progres memo',
                position: 'right',
            },

            ]
        }).start();

        intro.oncomplete(function () {
            alert("end of introduction");
        });
    }
}
