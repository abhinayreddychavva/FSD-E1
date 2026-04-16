package com.veltech.task8;

import org.springframework.context.annotation.AnnotationConfigApplicationContext;

import com.veltech.task8.controller.EmployeeController;

import org.springframework.beans.factory.BeanFactory;
/**
 * Hello world!
 *
 */

public class App {

    public static void main(String[] args) {
        AnnotationConfigApplicationContext context =new AnnotationConfigApplicationContext(AppConfig.class);
        BeanFactory factory = context;
        EmployeeController service = context.getBean(EmployeeController.class);
        //EmployeeController service = context.getBean(EmployeeController.class);
        service.addEmp(1, "Manoj", "hr");
        service.addEmp(2, "Rakesh", "it");
        service.addEmp(3, "Charan","finance");
        service.getAll();
        context.close();
    }
}