export const formatDate = (dateStr) => {
    if (!dateStr) return null;

    if (dateStr.includes("T")) {
    const [date, time] = dateStr.split("T");
    return `${date} ${time}:00`;
    }

    return dateStr;
};

export const convertToDatetimeLocal = (str) => {
    if (!str) return "";
    return str.replace(" ", "T").slice(0, 16);
};

export const formatToDateInput = (dateStr) => {
    if (!dateStr) return "";
    return dateStr.slice(0, 10);
};