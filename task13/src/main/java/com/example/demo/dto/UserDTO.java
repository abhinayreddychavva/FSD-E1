package com.example.demo.dto;

import jakarta.validation.constraints.*;

public class UserDTO {

    @NotBlank(message = "Name required")
    private String name;

    @Email(message = "Invalid email")
    private String email;

    @Size(min = 6, message = "Password too short")
    private String password;

	public String getName() {
		return name;
	}

	public void setName(String name) {
		this.name = name;
	}

	public String getEmail() {
		return email;
	}

	public void setEmail(String email) {
		this.email = email;
	}

	public String getPassword() {
		return password;
	}

	public void setPassword(String password) {
		this.password = password;
	}

    
}