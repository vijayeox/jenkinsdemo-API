package com.oxzion.routes

import org.springframework.http.HttpStatus
import org.springframework.web.bind.annotation.ResponseStatus


@ResponseStatus(value = HttpStatus.NOT_FOUND, reason="Job Does not Exists")
public class NotFoundException extends RuntimeException {
	
}