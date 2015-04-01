<?php /* Template Name: Suggestions1 */
$id =$_REQUEST['id'];
$post_7 = get_post_meta($id);
//print_r($post_7);

echo $post_7['savi_views_contact-details_first-name'][0].$post_7['savi_views_contact-details_last-name'][0]."|".$post_7['savi_views_contact-details_email'][0]."|".$post_7['savi_views_contact-details_nationality'][0]."|".
($post_7['savi_views_contact-details_phone-number-in-india'][0]!=$post_7['savi_views_contact-details_phone-number-in-india'][0]?:$post_7['savi_views_contact-details_phone-number'][0])."|".$post_7['savi_views_skills_english'][0].$post_7['savi_views_skills_professional-skills'][0].$post_7['savi_views_skills_computer-skills'][0].$post_7['savi_views_skills_personal-strengths'][0]."|".$post_7['savi_views_motivations_prior-knowledge-of-auroville'][0].$post_7['savi_views_motivations_expectations-of-stay'][0];
?>