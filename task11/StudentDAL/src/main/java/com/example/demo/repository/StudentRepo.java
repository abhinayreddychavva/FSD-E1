package com.example.demo.repository;

import java.util.List;

import org.springframework.data.jpa.repository.JpaRepository;

import com.example.demo.model.student;

public interface StudentRepo extends JpaRepository<student, Integer>{
	//automated query generation and application
	List<student>findByDept(String dept);
	List<student>findByAge(int age);
	List<student>findByAgeGreaterThan(int age);
	List<student>findByDeptAndAge(String dept,int age);
}