package com.oxzion.job

import org.quartz.JobDataMap
import org.quartz.JobExecutionContext
import org.quartz.JobExecutionException
import org.slf4j.Logger
import org.slf4j.LoggerFactory
import org.springframework.scheduling.quartz.QuartzJobBean

class JobHandler extends QuartzJobBean{

        private static final Logger logger = LoggerFactory.getLogger(JobHandler.class)

        @Override
        protected void executeInternal(JobExecutionContext jobExecutionContext) throws JobExecutionException {
            logger.info("Executing Job with key {}", jobExecutionContext.getJobDetail().getKey())
            JobDataMap jobDataMap = jobExecutionContext.getMergedJobDataMap()
            Set keys = jobDataMap.keySet()
            println("All keys are: " + keys)

        }

    }
