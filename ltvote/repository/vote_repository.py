from ltvote import domain, orm


class VoteRepository():

    def _vote_to_record(self, vote):
        if vote.id is None:
            record = orm.Vote(
                user_id=vote.user_id,
                conference_id=vote.conference_id,
                speaker_id=vote.speaker_id,
                term=vote.term,
                token=vote.token
            )
        else:
            record = orm.Vote(
                id=vote.id,
                user_id=vote.user_id,
                conference_id=vote.conference_id,
                speaker_id=vote.speaker_id,
                term=vote.term,
                token=vote.token
            )
        return record

    def _record_to_vote(self, record):
        return domain.Vote(
            id_=record.id,
            user_id=record.user_id,
            conference_id=record.conference_id,
            speaker_id=record.speaker_id,
            term=record.term,
            token=record.token,
            created_at=record.created_at,
            updated_at=record.updated_at
        )

    def __init__(self, session):
        self.session = session

    def save(self, vote):
        record = self.session.query(orm.Vote).filter_by(
            user_id=vote.user_id,
            conference_id=vote.conference_id,
            term=vote.term
        ).first()
        if record is not None:
            raise Exception('Already saved.')
        record = self._vote_to_record(vote)
        self.session.add(record)
        self.session.flush()
        self.session.commit()
        return self._record_to_vote(record)

    def update(self, vote):
        record = self.session.query(orm.Vote).filter_by(
            user_id=vote.user_id,
            conference_id=vote.conference_id,
            term=vote.term,
            token=vote.token
        ).first()
        vote.id = record.id
        record = self._vote_to_record(vote)
        self.session.merge(record)
        self.session.flush()
        self.session.commit()
        return self._record_to_vote(record)

    def get_votes(self, conference_id, term):
        records = self.session.query(orm.Vote).filter_by(
            conference_id=conference_id,
            term=term,
        ).all()
        if not records:
            return []

        votes = [self._record_to_vote(record) for record in records]
        return votes


class VoteRepositoryFactory():

    @classmethod
    def create(cls, session):
        return VoteRepository(session)
