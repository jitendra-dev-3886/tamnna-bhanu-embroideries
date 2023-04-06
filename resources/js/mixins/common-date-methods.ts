import Vue from "vue";
import Component from "vue-class-component";
import {
    format,
    differenceInMinutes,
    getTime,
    endOfDay,
    add,
    sub,
    parse,
    isMatch,
} from "date-fns";

@Component
class CommonDateMethod extends Vue {
    /* Current Time */
    currentTime(): string {
        return format(new Date(), this["$getConst"]("TIME_CONST"));
    }

    /* Current Date */
    currentDate(): string {
        return format(new Date(), this["$getConst"]("DATE_CONST"));
    }

    /* Current Date Time */
    currentDateTime(): string {
        return format(new Date(), this["$getConst"]("DATE_TIME_CONST"));
    }

    /* Format Date */
    getDateFormat(value: Date | string): string {
        return value
            ? format(new Date(value), this["$getConst"]("DATE_CONST"))
            : "";
    }
    
    /* Format Date */
    getCustomDateFormat(value: Date | string): string | Date {
        if (isMatch(value as string, "yyyy-MM-dd") === true) return value;

        return value
            ? format(
                  parse(
                      value as string,
                      this["$getConst"]("DATE_CONST"),
                      new Date()
                  ),
                  this["$getConst"]("FILTER_DATE_CONST")
              )
            : "";
    }

    /* Format Date Time */
    getDateTimeFormat(value: Date | string): string {
        return value
            ? format(new Date(value), this["$getConst"]("DATE_TIME_CONST"))
            : "";
    }

    /**
     * Get time differences of two times
     * @param dateLeft - Later Date
     * @param dateRight - Earlier Date
     * @param diffFormat - format type like minute/second/hour/days/etc
     * @returns {string|number}
     */
    getTimeDiff(
        dateLeft: Date | number,
        dateRight: Date | number,
        diffFormat: string
    ): string | number {
        if (diffFormat == "minutes") {
            return differenceInMinutes(dateLeft, dateRight);
        }
        return "";
    }

    /**
     * get Timestamp from the date
     * @param date
     * @returns {number}
     */
    getTimestampFromDate(date: Date | string): number | string {
        return date ? getTime(new Date(date)) / 1000 : "";
    }

    /**
     * get Timestamp from the date
     * @param date
     * @returns {number}
     */
    getTimestampFromDateString(dateStr: string): number | string {
        return dateStr
            ? getTime(
                  new Date(
                      this.getCustomDateFormat(dateStr.split(" ")[0]) +
                          " " +
                          this.convertTime12to24(
                              this.formatTime(dateStr.split(" ")[1]) +
                                  " " +
                                  dateStr.split(" ")[2]
                          )
                  )
              ) / 1000
            : "";
    }

    /**
     * get Timestamp of end of day from the date
     * @param date
     * @returns {number}
     */
    getEODTimestampFromDate(date: Date | string): number | string {
        return date ? getTime(endOfDay(new Date(date))) / 1000 : "";
    }

    computedDateFormattedMomentjs(date: Date | string): Date | string {
        return date
            ? format(new Date(date), this["$getConst"]("DATE_CONST"))
            : "";
    }

    // get Timestamp to Date For Specific Format
    getTimestampToDateForSpecificFormat(
        date: number | string,
        dateFormat = this.$getConst("DATE_CONST")
    ): Date | string {
        return date ? format(new Date(Number(date) * 1000), dateFormat) : "";
    }

    // Get Date From Timestamp
    getDateFromTimestamp(timestamp: string): string {
        return timestamp
            ? new Date(
                  parseInt(timestamp) * 1000 -
                      new Date().getTimezoneOffset() * 60000
              )
                  .toISOString()
                  .substr(0, 10)
            : "";
    }

    /* Format Date */
    getFilterDateFormat(
        date: Date | string,
        dateFormat = this.$getConst("FILTER_DATE_CONST")
    ): Date | string {
        return date ? format(new Date(date), dateFormat) : "";
    }

    customDayMonthDate(
        value: Date | string,
        count: number,
        parameter: string,
        isAdd: boolean
    ): string {
        if (value) {
            if (isAdd) {
                return format(
                    add(new Date(value), {
                        [parameter]: count,
                    }),
                    this["$getConst"]("DATE_CONST")
                );
            }
            return format(
                sub(new Date(value), {
                    [parameter]: count,
                }),
                this["$getConst"]("DATE_CONST")
            );
        }
        return "";
    }

    // Get Default Date For Date-time Picker
    defaultSelectedDate(): { [index: string]: string } {
        // Set the current date and time as default value
        var d = new Date();
        var currentHour = d.getHours() % 12; // AM,PM format
        var minutes = (d.getMinutes() < 10 ? "0" : "") + d.getMinutes();
        var currentTime = currentHour + ":" + minutes + ":00";
        var dateModel = d.toISOString().substr(0, 10);

        var meridian = "AM";
        if (d.getHours() >= 12) {
            meridian = "PM";
        }

        return {
            meridian: meridian,
            timeModel: currentTime,
            dateModel: dateModel,
        };
    }

    // Format Time to 12 hour format
    formatTime(str: string): string {
        const [time, meridian] = str.split(" ");

        let [hours, minutes, seconds] = time.split(":");
        if (hours === "00") {
            hours = "12";
        } else if (hours === "12") {
            hours = "00";
        } else if (parseInt(hours) > 12) {
            hours = (parseInt(hours) - 12).toString();
        }

        if (meridian) return `${hours}:${minutes}:${seconds} ${meridian}`;

        return `${hours}:${minutes}:${seconds}`;
    }

    // Convert 12 to 24 hours Format
    convertTime12to24 = (time12h) => {
        const [time, modifier] = time12h.split(" ");
        let [hours, minutes, seconds] = time.split(":");
        if (hours === "12") {
            hours = "00";
        }
        if (modifier === "PM") {
            hours = parseInt(hours, 10) + 12;
        }
        if (seconds) {
            seconds = seconds;
        } else {
            seconds = "00";
        }
        return `${hours}:${minutes}:${seconds}`;
    };
    
}

export default CommonDateMethod;
