import { useContext } from 'react';
import { ScheduleContext } from './ScheduleContext';

export const useSchedule = () => useContext(ScheduleContext);