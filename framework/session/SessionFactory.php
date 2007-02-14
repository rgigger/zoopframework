<?php
function SessionFactory()
{
	return new SessionPgsql();
}