<?php
include '../templates/func.php';
include '../templates/settings.php';

$title = "Feedback";
?>
<!DOCTYPE html>
<html lang="en">
<?php include "../templates/head.php"; ?>
<body class="icons_body">
    <!-- Main header -->
    <?php include "../templates/header.html"; ?>
    <!-- Report's navigation -->
    <nav class="reports_nav">
        <div class="search">
            <h1>Sort by:</h1>
            <select name="reports_filter">
                <option value="New">New</option>
                <option value="Old">Old</option>
                <option value="High rating">High rating</option>
                <option value="Low rating">Low rating</option>
            </select>
        </div>
        <!-- Block to leave feedback -->
        <div class="your_feedback">
            <h1>Leave your feedback:</h1>
            <?php if (check_the_login($user_data, '../', false)){ ?>
              <button>Leave</button>
            <?php }else{ ?>
              <a href="../log.php">Log in to leave the feedback</a>
            <?php } ?>
        </div>
    </nav>

    <!-- Popup window for user's feeadback -->

    <?php include "../templates/pop_up_report_window.php"; ?>

    <main>
        <!-- All reports block -->
        <div class="container all_reports">
            <?php
              $select_sql = "SELECT * FROM reports";
              include "../templates/report_list.php";
            ?>
        </div>
    </main>

    <?php include "../templates/footer.html"; ?>

    <script src="../templates/format.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-element-bundle.min.js"></script>
    <script>
        // Search
        const selectElement = document.querySelector('.reports_nav .search select');
        let reportsCaptionArr = document.querySelectorAll('.all_reports .reports_item .caption');
        let reportsDateArr = [];
        
        for(let i = 0; i < reportsCaptionArr.length; i++){
            let current = (reportsCaptionArr[i].innerHTML).split(' ');
            let day = parseInt(current[0]);
            let year = parseInt(current[2]);
            let month = 0;
            if(current[1] == "January"){
                month = 1;
            }
            if(current[1] == "February"){
                month = 2;
            }
            if(current[1] == "March"){
                month = 3;
            }
            if(current[1] == "April"){
                month = 4;
            }
            if(current[1] == "May"){
                month = 5;
            }
            if(current[1] == "June"){
                month = 6;
            }
            if(current[1] == "July"){
                month = 7;
            }
            if(current[1] == "August"){
                month = 8;
            }
            if(current[1] == "September"){
                month = 9;
            }
            if(current[1] == "October"){
                month = 10;
            }
            if(current[1] == "November"){
                month = 11;
            }
            if(current[1] == "December"){
                month = 12;
            }

            let days = day + month * 30 + year * 365;
            let item = {
                number: i,
                days: days
            };

            reportsDateArr.push(item);
        }

        // Date
        let reportsCover = document.querySelector('.all_reports');
        let reportsArr = document.querySelectorAll('.all_reports .reports_item');

        // Rating
        let reportsRatingArr = document.querySelectorAll('.all_reports .reports_item .content .rating p');
        let SortRatingArr = []

        for(let i = 0; i < reportsRatingArr.length; i++){
            let current = (reportsRatingArr[i].innerHTML).split(' / ');

            let item = {
                number: i,
                rating: current[0]
            }

            SortRatingArr.push(item);
        }

        selectElement.addEventListener("change", (event) => {
            if (event.target.value == "Old"){
                reportsDateArr.sort((a, b) => a.days > b.days ? 1 : -1);

                reportsCover.innerHTML = '';
                for(let i = 0; i < reportsDateArr.length; i++){
                    reportsCover.appendChild(reportsArr[reportsDateArr[i].number]);
                }
            }

            if (event.target.value == "New"){
                reportsDateArr.sort((a, b) => a.days < b.days ? 1 : -1);

                reportsCover.innerHTML = '';
                for(let i = 0; i < reportsDateArr.length; i++){
                    reportsCover.appendChild(reportsArr[reportsDateArr[i].number]);
                }
            }

            if (event.target.value == "High rating"){
                SortRatingArr.sort((a, b) => a.rating < b.rating ? 1 : -1);

                reportsCover.innerHTML = '';
                for(let i = 0; i < SortRatingArr.length; i++){
                    reportsCover.appendChild(reportsArr[SortRatingArr[i].number]);
                }
            }

            if (event.target.value == "Low rating"){
                SortRatingArr.sort((a, b) => a.rating > b.rating ? 1 : -1);

                reportsCover.innerHTML = '';
                for(let i = 0; i < SortRatingArr.length; i++){
                    reportsCover.appendChild(reportsArr[SortRatingArr[i].number]);
                }
            }

            reportsCaptionArr = document.querySelectorAll('.all_reports .reports_item .caption');
            reportsDateArr = [];
            
            for(let i = 0; i < reportsCaptionArr.length; i++){
                let current = (reportsCaptionArr[i].innerHTML).split(' ');
                let day = parseInt(current[0]);
                let year = parseInt(current[2]);
                let month = 0;
                if(current[1] == "January"){
                    month = 1;
                }
                if(current[1] == "February"){
                    month = 2;
                }
                if(current[1] == "March"){
                    month = 3;
                }
                if(current[1] == "April"){
                    month = 4;
                }
                if(current[1] == "May"){
                    month = 5;
                }
                if(current[1] == "June"){
                    month = 6;
                }
                if(current[1] == "July"){
                    month = 7;
                }
                if(current[1] == "August"){
                    month = 8;
                }
                if(current[1] == "September"){
                    month = 9;
                }
                if(current[1] == "October"){
                    month = 10;
                }
                if(current[1] == "November"){
                    month = 11;
                }
                if(current[1] == "December"){
                    month = 12;
                }

                let days = day + month * 30 + year * 365;
                let item = {
                    number: i,
                    days: days
                };

                reportsDateArr.push(item);
            }

            // Date
            reportsCover = document.querySelector('.all_reports');
            reportsArr = document.querySelectorAll('.all_reports .reports_item');

            reportsArr = document.querySelectorAll('.all_reports .reports_item');

            // Rating
            reportsRatingArr = document.querySelectorAll('.all_reports .reports_item .content .rating p');
            SortRatingArr = []

            for(let i = 0; i < reportsRatingArr.length; i++){
                let current = (reportsRatingArr[i].innerHTML).split(' / ');

                let item = {
                    number: i,
                    rating: current[0]
                }

                SortRatingArr.push(item);
            }
        });
    </script>
    <script src="../templates/pop_up_report_script.js"></script>
</body>
</html>