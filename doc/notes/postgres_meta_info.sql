http://www.alberton.info/postgresql_meta_info.html

SELECT * from information_schema.tables where table_schema = 'public';
SELECT * from information_schema.columns where table_schema = 'public';
SELECT tc.constraint_name,
          tc.constraint_type,
          tc.table_name,
          kcu.column_name,
	  tc.is_deferrable,
          tc.initially_deferred,
          rc.match_option AS match_type,
          rc.update_rule AS on_update,
          rc.delete_rule AS on_delete,
          ccu.table_name AS references_table,
          ccu.column_name AS references_field
     FROM information_schema.table_constraints tc
LEFT JOIN information_schema.key_column_usage kcu
       ON tc.constraint_catalog = kcu.constraint_catalog
      AND tc.constraint_schema = kcu.constraint_schema
      AND tc.constraint_name = kcu.constraint_name
LEFT JOIN information_schema.referential_constraints rc
       ON tc.constraint_catalog = rc.constraint_catalog
      AND tc.constraint_schema = rc.constraint_schema
      AND tc.constraint_name = rc.constraint_name
LEFT JOIN information_schema.constraint_column_usage ccu
       ON rc.unique_constraint_catalog = ccu.constraint_catalog
      AND rc.unique_constraint_schema = ccu.constraint_schema
      AND rc.unique_constraint_name = ccu.constraint_name
where constraint_type in ('FOREIGN KEY', 'PRIMARY KEY');