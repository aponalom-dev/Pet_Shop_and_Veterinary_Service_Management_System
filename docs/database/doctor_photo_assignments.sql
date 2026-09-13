-- Apply to an existing pawcare_db after the portrait files are in assets/uploads/profiles/.
START TRANSACTION;
UPDATE users SET profile_image = 'Apon.jpg'
WHERE username = 'dr_hasan' AND role = 'doctor';

UPDATE users SET profile_image = 'Era.jpg'
WHERE username = 'dr_nusrat' AND role = 'doctor';

UPDATE users SET profile_image = 'Mostofa.jpg'
WHERE full_name = 'Syed Shahriar Mustafa' AND role = 'doctor';

COMMIT;
