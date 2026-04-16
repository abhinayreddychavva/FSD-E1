package com.example.demo.controller;


import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.servlet.ModelAndView;

import com.example.demo.model.Employee;
import com.example.demo.repo.EmployeeRepo;

@Controller
public class WebController {
//	@Autowired
	EmployeeRepo repo=new EmployeeRepo();
	
    @RequestMapping("/")
    public String home() {
        return "home.jsp";
    }
    @RequestMapping("/get")
    public String show() {
    	return "show.jsp";
    }
    @RequestMapping("/addEmployee")
    public String addEmployee(Employee emp) {
    	repo.addEmployee(emp);
    	return "home.jsp";
    }
    @RequestMapping("/getEmployee")
    public ModelAndView getEmployee(@RequestParam("id") int id) {
    	ModelAndView mv=new ModelAndView("show.jsp");
    	Employee emp=repo.getEmployee(id);
    	mv.addObject("employee", emp);
    	return mv;
    }
}