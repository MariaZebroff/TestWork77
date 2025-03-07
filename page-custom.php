<?php
/**
 * Template Name: Custom Page
 * Description: A custom template.
 */

get_header(); 
?>

<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
        <h1><?php the_title(); ?></h1>
        Template
		<?php
			while ( have_posts() ) :
				the_post();

                the_content();

			endwhile; // End of the loop.
			?>

<div class="cities-table-container">
    <h2>List of Cities, Countries, and Weather</h2>
    <!-- Search Input -->
     <div class="py-20">
         <input type="text" id="city-search" placeholder="Search for a city..." /> <button id="reset">Clear</button>
     </div>
     <?php do_action('city_action_before_table');?>
    <table class="cities-table">
        <thead>
            <tr>
                <th>City</th>
                <th>Country</th>
                <th>Temperature</th>
            </tr>
        </thead>
        <tbody id="cities-table-body">
        
        </tbody>
    </table>
    <?php do_action('city_action_after_table');?>
</div>

<style>
    .cities-table-container {
        max-width: 800px;
        margin: 20px auto;
    }
    .cities-table {
        width: 100%;
        border-collapse: collapse;
    }
    .cities-table th, .cities-table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }
    .cities-table th {
        background-color: #f4f4f4;
    }
    .py-20 {
        padding: 20px 0;
    }
</style>

		</main><!-- #main -->
	</div><!-- #primary -->

   

<?php

get_footer();