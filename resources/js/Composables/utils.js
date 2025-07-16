// composables/utils.js

function getStatusLabel(info) {
    if (info.complain) return 'Complaint Registered'

    if (info.aspio_in && !info.spio_in) {
        return 'Under process by SAPIO'
    }

    if (info.spio_in && !info.spio_out) {
        return info.information_status == 11
            ? '30 days has passed'
            : 'Under process by SPIO'
    }

    if (info.spio_answer && !info.first_appeal_citizen_question && info.spio_out) {
        return info.information_status == 11
            ? '30 days has passed (SPIO)'
            : 'Answered by SPIO'
    }

    if (info.first_appeal_citizen_question && !info.first_appeal_daa_answer) {
        return 'Under process by DAA'
    }

    if (!info.second_appeal_citizen_question && info.first_appeal_daa_answer) {
        return info.information_status == 22
            ? '30 days has passed (DAA)'
            : 'Answered by DAA'
    }

    if (info.second_appeal_citizen_question && !info.second_appeal_cic_answer) {
        return 'Under process by CIC'
    }

    if (info.second_appeal_citizen_question && info.second_appeal_cic_answer) {
        return 'Answered by CIC'
    }

    if (!info.aspio_answer && !info.aspio_in && info.spio_in && !info.spio_out) {
        return 'Under process by SPIO'
    }

    if (!info.aspio_answer && !info.aspio_in && info.spio_in && info.spio_out) {
        return 'Answered by SPIO'
    }

    return 'Pending'
}

function getStatusClass(info) {
    const label = getStatusLabel(info).toLowerCase()

    if (label.includes('under process')) {
        return 'bg-red-100 text-red-600'
    }
    if (label.includes('answered')) {
        return 'bg-green-100 text-green-700'
    }
    if (label.includes('30 days')) {
        return 'bg-yellow-100 text-yellow-700'
    }
    if (label.includes('complaint')) {
        return 'bg-purple-100 text-purple-700'
    }
    return 'bg-gray-100 text-gray-700'
}

function getTimeStatus(info) {
    const now = new Date()

    const stages = [
        {
            field: 'aspio_in',
            answered: info.aspio_answer != null,
            condition: !info.aspio_answer && !info.spio_in && info.aspio_in,
            outputType: 'image+text',
        },
        {
            field: 'spio_in',
            answered: info.spio_answer != null,
            condition: !info.spio_answer && info.spio_in,
            outputType: 'image+text',
        },
        {
            field: 'spio_out',
            answered: true,
            condition: info.spio_answer && !info.first_appeal_citizen_question,
            outputType: 'answered',
            isComputerGenerated: info.information_status === 11,
        },
        {
            field: 'first_appeal_daa_in',
            answered: info.first_appeal_daa_answer != null,
            condition: info.first_appeal_citizen_question && !info.first_appeal_daa_answer,
            outputType: 'image+text',
        },
        {
            field: 'first_appeal_daa_out',
            answered: true,
            condition: info.first_appeal_daa_answer && !info.second_appeal_citizen_question,
            outputType: 'answered',
            isComputerGenerated: info.information_status === 22,
        },
        {
            field: 'second_appeal_cic_in',
            answered: info.second_appeal_cic_answer != null,
            condition: info.second_appeal_citizen_question && !info.second_appeal_cic_answer,
            outputType: 'image+text',
        },
        {
            field: 'second_appeal_cic_out',
            answered: true,
            condition: info.second_appeal_citizen_question && info.second_appeal_cic_answer,
            outputType: 'answered',
        },
    ]

    for (const stage of stages) {
        const raw = info[stage.field]
        if (stage.condition && raw) {
            const date = new Date(raw)
            if (isNaN(date)) return { image: null, text: 'Invalid date' }

            const days = Math.floor((now - date) / (1000 * 60 * 60 * 24))

            if (stage.outputType === 'answered') {
                let text = 'Answered at ' + formatDateTime(date)
                if (stage.isComputerGenerated) {
                    text += ' (Computer generated answer)'
                }
                return {
                    image: null,
                    text,
                }
            }

            return {
                image: getBadgeImage(days),
                text: days === 0 ? 'Today' : days === 1 ? '1 day ago' : `${days} days ago`,
            }
        }
    }

    return { image: null, text: 'Pending' } // fallback
}

function getBadgeImage(days) {
    if (days <= 5) return '/images/one.png'
    if (days <= 10) return '/images/two.png'
    if (days <= 15) return '/images/three.png'
    if (days <= 20) return '/images/four.png'
    if (days <= 25) return '/images/five.png'
    return '/images/six.png'
}

function formatDateTime(date) {
    return date.toLocaleString('en-IN', {
        day: 'numeric',
        month: 'short',
        year: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: true,
    })
}

// ✅ Proper export
export default function useUtils() {
    return {
        getStatusLabel,
        getStatusClass,
        getTimeStatus,
    }
}
